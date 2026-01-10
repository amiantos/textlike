// Game state store for Textlike
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type {
  Character,
  Room,
  Mob,
  Weapon,
  Armor,
  Tome,
  Consumable,
  Chest,
  BattleLogEntry,
  HighScore,
  Direction,
} from '../engine/types'
import { createCharacter, calculateCurrentWeight, countBandages, countApples, calculateScore, isEncumbered, getCharacterInventory, spendStatPoint, healWithApple, useBandages } from '../engine/character'
import { createInitialRoom, generateExitsForRoom, generateChest, shouldSpawnChest, shouldBeSpecialChest, getFloorDisplay, generateIntroStatement } from '../engine/dungeon'
import { generateMobWithEquipment, getMobSpawnCount } from '../engine/mobs'
import { generateSpecialChestLoot, generateNormalChestLoot } from '../engine/items'
import { executeCombatTurn, executeExplorationTurn, createBattleLogEntry } from '../engine/combat'
import { generateId } from '../engine/utils'

const STORAGE_KEY = 'textlike-save'

export const useGameStore = defineStore('game', () => {
  // ============================================================================
  // STATE
  // ============================================================================

  const character = ref<Character | null>(null)
  const rooms = ref<Map<string, Room>>(new Map())
  const mobs = ref<Map<string, Mob>>(new Map())
  const weapons = ref<Map<string, Weapon>>(new Map())
  const armors = ref<Map<string, Armor>>(new Map())
  const tomes = ref<Map<string, Tome>>(new Map())
  const consumables = ref<Map<string, Consumable>>(new Map())
  const chests = ref<Map<string, Chest>>(new Map())
  const battleLog = ref<BattleLogEntry[]>([])
  const highScores = ref<HighScore[]>([])
  const gameStarted = ref(false)
  const introMessage = ref<string>('')
  const isDefending = ref(false)

  // ============================================================================
  // COMPUTED
  // ============================================================================

  const currentRoom = computed(() => {
    if (!character.value?.currentRoomId) return null
    return rooms.value.get(character.value.currentRoomId) ?? null
  })

  const currentFloor = computed(() => {
    if (!currentRoom.value) return '25'
    return getFloorDisplay(currentRoom.value.level)
  })

  const currentMob = computed(() => {
    if (!character.value?.attackingMobId) return null
    return mobs.value.get(character.value.attackingMobId) ?? null
  })

  const isInCombat = computed(() => {
    return character.value?.attackingMobId !== null && currentMob.value !== null && !currentMob.value.isCorpse
  })

  const currentWeight = computed(() => {
    if (!character.value) return 0
    return calculateCurrentWeight(character.value, weapons.value, armors.value, tomes.value)
  })

  const isPlayerEncumbered = computed(() => {
    if (!character.value) return false
    return isEncumbered(character.value, weapons.value, armors.value, tomes.value)
  })

  const bandagesCount = computed(() => {
    if (!character.value) return 0
    return countBandages(character.value.id, consumables.value)
  })

  const applesCount = computed(() => {
    if (!character.value) return 0
    return countApples(character.value.id, consumables.value)
  })

  const score = computed(() => {
    if (!character.value) return 0
    return calculateScore(character.value)
  })

  const equippedWeapon = computed(() => {
    if (!character.value?.equippedWeaponId) return null
    return weapons.value.get(character.value.equippedWeaponId) ?? null
  })

  const equippedArmor = computed(() => {
    if (!character.value?.equippedArmorId) return null
    return armors.value.get(character.value.equippedArmorId) ?? null
  })

  const equippedTome = computed(() => {
    if (!character.value?.equippedTomeId) return null
    return tomes.value.get(character.value.equippedTomeId) ?? null
  })

  const inventory = computed(() => {
    if (!character.value) return { weapons: [], armors: [], tomes: [], consumables: [] }
    return getCharacterInventory(character.value.id, weapons.value, armors.value, tomes.value, consumables.value)
  })

  const roomMobs = computed(() => {
    if (!currentRoom.value) return []
    return Array.from(mobs.value.values()).filter(m => m.roomId === currentRoom.value!.id && !m.isCorpse)
  })

  const roomCorpses = computed(() => {
    if (!currentRoom.value) return []
    return Array.from(mobs.value.values()).filter(m => m.roomId === currentRoom.value!.id && m.isCorpse)
  })

  const roomItems = computed(() => {
    if (!currentRoom.value) return { weapons: [], armors: [], tomes: [], consumables: [] }
    const roomId = currentRoom.value.id
    return {
      weapons: Array.from(weapons.value.values()).filter(w => w.location.type === 'room' && w.location.id === roomId),
      armors: Array.from(armors.value.values()).filter(a => a.location.type === 'room' && a.location.id === roomId),
      tomes: Array.from(tomes.value.values()).filter(t => t.location.type === 'room' && t.location.id === roomId),
      consumables: Array.from(consumables.value.values()).filter(c => c.location.type === 'room' && c.location.id === roomId),
    }
  })

  const roomChests = computed(() => {
    if (!currentRoom.value) return []
    return Array.from(chests.value.values()).filter(c => c.roomId === currentRoom.value!.id && !c.isOpened)
  })

  const mobWeapon = computed(() => {
    if (!currentMob.value?.equippedWeaponId) return null
    return weapons.value.get(currentMob.value.equippedWeaponId) ?? null
  })

  const mobArmor = computed(() => {
    if (!currentMob.value?.equippedArmorId) return null
    return armors.value.get(currentMob.value.equippedArmorId) ?? null
  })

  // ============================================================================
  // ACTIONS
  // ============================================================================

  function startNewGame(characterName: string) {
    // Reset state
    rooms.value.clear()
    mobs.value.clear()
    weapons.value.clear()
    armors.value.clear()
    tomes.value.clear()
    consumables.value.clear()
    chests.value.clear()
    battleLog.value = []
    isDefending.value = false

    // Create character
    character.value = createCharacter(characterName)

    // Create initial room
    const initialRoom = createInitialRoom()
    rooms.value.set(initialRoom.id, initialRoom)
    character.value.currentRoomId = initialRoom.id

    // Generate exits for initial room
    const newRooms = generateExitsForRoom(initialRoom, rooms.value)
    for (const room of newRooms) {
      rooms.value.set(room.id, room)
    }

    // Generate starting chest in first room
    const startingChest = generateChest(initialRoom.id, true) // Special chest for start
    chests.value.set(startingChest.id, startingChest)

    // Set intro message
    introMessage.value = generateIntroStatement()

    gameStarted.value = true
    saveGame()
  }

  function moveToRoom(direction: Direction) {
    if (!character.value || !currentRoom.value || isInCombat.value || isPlayerEncumbered.value) return
    if (roomMobs.value.length > 0) return // Can't leave room with enemies

    // Generate exits for current room if not done
    if (!currentRoom.value.roomsGenerated) {
      const newRooms = generateExitsForRoom(currentRoom.value, rooms.value)
      for (const room of newRooms) {
        rooms.value.set(room.id, room)
      }
    }

    // Check if exit exists after generation
    const targetRoomId = currentRoom.value.exits[direction]
    if (!targetRoomId) return

    const targetRoom = rooms.value.get(targetRoomId)
    if (!targetRoom) return

    // Move to new room
    character.value.currentRoomId = targetRoom.id

    // Generate mobs if not done
    if (!targetRoom.mobsGenerated) {
      const mobCount = getMobSpawnCount(character.value.level)
      for (let i = 0; i < mobCount; i++) {
        const { mob, weapon, armor } = generateMobWithEquipment(targetRoom.level, targetRoom.id)
        mobs.value.set(mob.id, mob)
        weapons.value.set(weapon.id, weapon)
        armors.value.set(armor.id, armor)
      }
      targetRoom.mobsGenerated = true

      // Maybe spawn a chest
      if (shouldSpawnChest()) {
        const isSpecial = shouldBeSpecialChest()
        const chest = generateChest(targetRoom.id, isSpecial)
        chests.value.set(chest.id, chest)
      }
    }

    // Generate exits for new room
    if (!targetRoom.roomsGenerated) {
      const newRooms = generateExitsForRoom(targetRoom, rooms.value)
      for (const room of newRooms) {
        rooms.value.set(room.id, room)
      }
    }

    // Clear intro message after first move
    introMessage.value = ''

    // Process exploration turn
    executeExplorationTurn(character.value)

    saveGame()
  }

  function attackMob(mobId: string) {
    if (!character.value || !currentRoom.value) return

    const mob = mobs.value.get(mobId)
    if (!mob || mob.isCorpse) return

    // Start combat
    character.value.attackingMobId = mobId
    battleLog.value = []

    addBattleLog('info', `You engage the ${mob.name}!`)
    saveGame()
  }

  function performCombatAction(action: 'attack' | 'defend' | 'tome') {
    if (!character.value || !currentMob.value) return

    const result = executeCombatTurn(
      action,
      character.value,
      currentMob.value,
      equippedWeapon.value,
      equippedArmor.value,
      equippedTome.value,
      mobWeapon.value,
      mobArmor.value
    )

    // Add messages to battle log
    for (const msg of result.messages) {
      const type = msg.includes('You') && (msg.includes('strike') || msg.includes('cast'))
        ? 'attack'
        : msg.includes('attacks')
          ? 'mobAttack'
          : msg.includes('bleed')
            ? 'bleeding'
            : msg.includes('died') || msg.includes('collapses')
              ? 'death'
              : 'info'
      addBattleLog(type as BattleLogEntry['type'], msg)
    }

    // Handle broken equipment
    if (equippedWeapon.value && equippedWeapon.value.currentDurability <= 0) {
      character.value.equippedWeaponId = null
    }
    if (equippedArmor.value && equippedArmor.value.currentDurability <= 0) {
      character.value.equippedArmorId = null
    }
    if (equippedTome.value && equippedTome.value.currentDurability <= 0) {
      character.value.equippedTomeId = null
    }

    // End combat if mob died
    if (result.mobDied) {
      character.value.attackingMobId = null

      // Drop mob equipment to room
      if (mobWeapon.value) {
        mobWeapon.value.location = { type: 'room', id: currentRoom.value!.id }
      }
      if (mobArmor.value) {
        mobArmor.value.location = { type: 'room', id: currentRoom.value!.id }
      }
    }

    saveGame()
  }

  function useBandage() {
    if (!character.value || bandagesCount.value <= 0) return

    // Find and consume a bandage
    const bandage = Array.from(consumables.value.values()).find(
      c => c.location.type === 'character' && c.location.id === character.value!.id && c.kind === 'Bandages'
    )
    if (bandage) {
      consumables.value.delete(bandage.id)
      useBandages(character.value)
      addBattleLog('bandage', 'You apply bandages to your wounds.')

      // Process turn if in combat
      if (isInCombat.value && currentMob.value) {
        const result = executeCombatTurn('defend', character.value, currentMob.value, null, equippedArmor.value, null, mobWeapon.value, mobArmor.value)
        for (const msg of result.messages.slice(1)) { // Skip the "steel yourself" message
          addBattleLog('info', msg)
        }
      }

      saveGame()
    }
  }

  function useApple() {
    if (!character.value || applesCount.value <= 0) return

    // Find and consume an apple
    const apple = Array.from(consumables.value.values()).find(
      c => c.location.type === 'character' && c.location.id === character.value!.id && c.kind === 'Apples'
    )
    if (apple) {
      consumables.value.delete(apple.id)
      const healed = healWithApple(character.value)
      addBattleLog('apple', `You eat an apple and restore ${healed} health.`)

      // Process turn if in combat
      if (isInCombat.value && currentMob.value) {
        const result = executeCombatTurn('defend', character.value, currentMob.value, null, equippedArmor.value, null, mobWeapon.value, mobArmor.value)
        for (const msg of result.messages.slice(1)) {
          addBattleLog('info', msg)
        }
      }

      saveGame()
    }
  }

  function openChest(chestId: string) {
    if (!character.value || !currentRoom.value) return

    const chest = chests.value.get(chestId)
    if (!chest || chest.isOpened) return

    // Generate loot
    const loot = chest.isSpecial
      ? generateSpecialChestLoot(currentRoom.value.level, currentRoom.value.id)
      : generateNormalChestLoot(currentRoom.value.level, currentRoom.value.id)

    // Add loot to room
    for (const weapon of loot.weapons) {
      weapons.value.set(weapon.id, weapon)
    }
    for (const armor of loot.armors) {
      armors.value.set(armor.id, armor)
    }
    for (const tome of loot.tomes) {
      tomes.value.set(tome.id, tome)
    }
    for (const consumable of loot.consumables) {
      consumables.value.set(consumable.id, consumable)
    }

    chest.isOpened = true
    saveGame()
  }

  function pickUpWeapon(weaponId: string) {
    if (!character.value) return
    const weapon = weapons.value.get(weaponId)
    if (!weapon || weapon.location.type !== 'room') return

    weapon.location = { type: 'character', id: character.value.id }
    saveGame()
  }

  function pickUpArmor(armorId: string) {
    if (!character.value) return
    const armor = armors.value.get(armorId)
    if (!armor || armor.location.type !== 'room') return

    armor.location = { type: 'character', id: character.value.id }
    saveGame()
  }

  function pickUpTome(tomeId: string) {
    if (!character.value) return
    const tome = tomes.value.get(tomeId)
    if (!tome || tome.location.type !== 'room') return

    tome.location = { type: 'character', id: character.value.id }
    saveGame()
  }

  function pickUpConsumable(consumableId: string) {
    if (!character.value) return
    const consumable = consumables.value.get(consumableId)
    if (!consumable || consumable.location.type !== 'room') return

    consumable.location = { type: 'character', id: character.value.id }
    saveGame()
  }

  function equipWeapon(weaponId: string) {
    if (!character.value) return
    const weapon = weapons.value.get(weaponId)
    if (!weapon || weapon.location.type !== 'character' || weapon.location.id !== character.value.id) return

    character.value.equippedWeaponId = weaponId
    saveGame()
  }

  function equipArmor(armorId: string) {
    if (!character.value) return
    const armor = armors.value.get(armorId)
    if (!armor || armor.location.type !== 'character' || armor.location.id !== character.value.id) return

    character.value.equippedArmorId = armorId
    saveGame()
  }

  function equipTome(tomeId: string) {
    if (!character.value) return
    const tome = tomes.value.get(tomeId)
    if (!tome || tome.location.type !== 'character' || tome.location.id !== character.value.id) return

    character.value.equippedTomeId = tomeId
    saveGame()
  }

  function dropWeapon(weaponId: string) {
    if (!character.value || !currentRoom.value) return
    const weapon = weapons.value.get(weaponId)
    if (!weapon) return

    if (character.value.equippedWeaponId === weaponId) {
      character.value.equippedWeaponId = null
    }
    weapon.location = { type: 'room', id: currentRoom.value.id }
    saveGame()
  }

  function dropArmor(armorId: string) {
    if (!character.value || !currentRoom.value) return
    const armor = armors.value.get(armorId)
    if (!armor) return

    if (character.value.equippedArmorId === armorId) {
      character.value.equippedArmorId = null
    }
    armor.location = { type: 'room', id: currentRoom.value.id }
    saveGame()
  }

  function dropTome(tomeId: string) {
    if (!character.value || !currentRoom.value) return
    const tome = tomes.value.get(tomeId)
    if (!tome) return

    if (character.value.equippedTomeId === tomeId) {
      character.value.equippedTomeId = null
    }
    tome.location = { type: 'room', id: currentRoom.value.id }
    saveGame()
  }

  function allocateStatPoint(stat: 'strength' | 'stamina' | 'dexterity' | 'intelligence' | 'luck') {
    if (!character.value) return
    spendStatPoint(character.value, stat)
    saveGame()
  }

  function addBattleLog(type: BattleLogEntry['type'], message: string) {
    const turn = character.value?.turns ?? 0
    battleLog.value.push(createBattleLogEntry(turn, type, message))
  }

  function submitScore() {
    if (!character.value) return

    const newScore: HighScore = {
      id: generateId(),
      characterName: character.value.name,
      score: score.value,
      level: character.value.level,
      floor: currentFloor.value,
      date: Date.now(),
    }

    highScores.value.push(newScore)
    highScores.value.sort((a, b) => b.score - a.score)
    highScores.value = highScores.value.slice(0, 10) // Keep top 10

    // Clear character
    character.value = null
    gameStarted.value = false

    saveGame()
  }

  // ============================================================================
  // PERSISTENCE
  // ============================================================================

  function saveGame() {
    const state = {
      character: character.value,
      rooms: Array.from(rooms.value.entries()),
      mobs: Array.from(mobs.value.entries()),
      weapons: Array.from(weapons.value.entries()),
      armors: Array.from(armors.value.entries()),
      tomes: Array.from(tomes.value.entries()),
      consumables: Array.from(consumables.value.entries()),
      chests: Array.from(chests.value.entries()),
      battleLog: battleLog.value,
      highScores: highScores.value,
      gameStarted: gameStarted.value,
      introMessage: introMessage.value,
    }
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state))
  }

  function loadGame(): boolean {
    const saved = localStorage.getItem(STORAGE_KEY)
    if (!saved) return false

    try {
      const state = JSON.parse(saved)
      character.value = state.character
      rooms.value = new Map(state.rooms)
      mobs.value = new Map(state.mobs)
      weapons.value = new Map(state.weapons)
      armors.value = new Map(state.armors)
      tomes.value = new Map(state.tomes)
      consumables.value = new Map(state.consumables)
      chests.value = new Map(state.chests)
      battleLog.value = state.battleLog || []
      highScores.value = state.highScores || []
      gameStarted.value = state.gameStarted
      introMessage.value = state.introMessage || ''
      return true
    } catch {
      return false
    }
  }

  function hasSavedGame(): boolean {
    return localStorage.getItem(STORAGE_KEY) !== null && gameStarted.value === false
  }

  function clearSave() {
    localStorage.removeItem(STORAGE_KEY)
  }

  // ============================================================================
  // RETURN
  // ============================================================================

  return {
    // State
    character,
    rooms,
    mobs,
    weapons,
    armors,
    tomes,
    consumables,
    chests,
    battleLog,
    highScores,
    gameStarted,
    introMessage,

    // Computed
    currentRoom,
    currentFloor,
    currentMob,
    isInCombat,
    currentWeight,
    isPlayerEncumbered,
    bandagesCount,
    applesCount,
    score,
    equippedWeapon,
    equippedArmor,
    equippedTome,
    inventory,
    roomMobs,
    roomCorpses,
    roomItems,
    roomChests,
    mobWeapon,
    mobArmor,

    // Actions
    startNewGame,
    moveToRoom,
    attackMob,
    performCombatAction,
    useBandage,
    useApple,
    openChest,
    pickUpWeapon,
    pickUpArmor,
    pickUpTome,
    pickUpConsumable,
    equipWeapon,
    equipArmor,
    equipTome,
    dropWeapon,
    dropArmor,
    dropTome,
    allocateStatPoint,
    submitScore,
    saveGame,
    loadGame,
    hasSavedGame,
    clearSave,
  }
})
