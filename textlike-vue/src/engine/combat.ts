// Combat system for Textlike
// Based on original PHP: attacking.php, bleeding.php, endturn.php

import type {
  Character,
  Mob,
  Weapon,
  Armor,
  Tome,
  BodyPart,
  BattleLogEntry,
} from './types'
import { generateId, diceRoll, randInt, roundTo } from './utils'
import { damageWeapon, damageArmor, damageTome } from './items'
import { calculateMobBleeding } from './mobs'
import {
  calculateBleeding,
  healWounds,
  autoHeal,
  killCharacter,
  addExperience,
} from './character'

// ============================================================================
// COMBAT RESULT TYPES
// ============================================================================

export interface AttackResult {
  hit: boolean
  damage: number
  bodyPart: BodyPart | null
  weaponBroke: boolean
  armorBroke: boolean
  message: string
}

export interface TomeAttackResult {
  hit: boolean
  damage: number
  tomeBroke: boolean
  wasEffective: boolean // Hit weakness
  wasResisted: boolean // Hit resistance
  message: string
}

export interface TurnResult {
  playerAttackResult: AttackResult | TomeAttackResult | null
  mobAttackResult: AttackResult | null
  playerBleedDamage: number
  mobBleedDamage: number
  playerDied: boolean
  mobDied: boolean
  experienceGained: number
  leveledUp: boolean
  messages: string[]
}

// ============================================================================
// HIT CALCULATIONS
// ============================================================================

/**
 * Check if player attack hits
 * Player rolls 2d6 + (playerDex - mobDex), mob rolls 1d6
 * Hit if player roll >= mob roll
 */
export function playerAttackHits(playerDex: number, mobDex: number): boolean {
  const playerRoll = diceRoll(2) + (playerDex - mobDex)
  const mobRoll = diceRoll(1)
  return playerRoll >= mobRoll
}

/**
 * Check if tome attack hits (better accuracy than weapon)
 * Player rolls 3d6 + (playerDex - mobDex), mob rolls 1d6
 */
export function tomeAttackHits(playerDex: number, mobDex: number): boolean {
  const playerRoll = diceRoll(3) + (playerDex - mobDex)
  const mobRoll = diceRoll(1)
  return playerRoll >= mobRoll
}

/**
 * Check if mob attack hits
 * Mob rolls 2d6, player rolls 1d6 + (playerDex - mobDex)
 * Hit if mob roll > player roll
 */
export function mobAttackHits(playerDex: number, mobDex: number, isDefending: boolean): boolean {
  // Defense swaps dice counts
  const mobDice = isDefending ? 1 : 2
  const playerDice = isDefending ? 2 : 1

  const mobRoll = diceRoll(mobDice)
  const playerRoll = diceRoll(playerDice) + (playerDex - mobDex)

  return mobRoll > playerRoll
}

// ============================================================================
// BODY PART TARGETING
// ============================================================================

/**
 * Select a random body part to hit
 * Weighted distribution from original game
 */
export function selectBodyPart(): BodyPart {
  const roll = randInt(1, 100)

  if (roll >= 95) return 'head' // 6%
  if (roll >= 80) return 'torso' // 15%
  if (roll >= 60) return 'leftLeg' // 20%
  if (roll >= 40) return 'rightLeg' // 20%
  if (roll >= 20) return 'rightArm' // 20%
  return 'leftArm' // 19%
}

/**
 * Get display name for body part
 */
export function getBodyPartName(part: BodyPart): string {
  const names: Record<BodyPart, string> = {
    head: 'head',
    torso: 'torso',
    leftArm: 'left arm',
    rightArm: 'right arm',
    leftLeg: 'left leg',
    rightLeg: 'right leg',
  }
  return names[part]
}

// ============================================================================
// DAMAGE CALCULATIONS
// ============================================================================

/**
 * Calculate weapon attack damage
 * damage = weaponDamage + (strength * 0.5)
 * finalDamage = max(0, damage - armorProtection)
 */
export function calculateWeaponDamage(
  weaponDamage: number,
  attackerStrength: number,
  armorProtection: number
): number {
  const baseDamage = weaponDamage + attackerStrength * 0.5
  return Math.max(0, roundTo(baseDamage - armorProtection, 1))
}

/**
 * Calculate unarmed (punch) damage
 * Just strength-based
 */
export function calculatePunchDamage(strength: number, armorProtection: number): number {
  const baseDamage = strength * 0.3
  return Math.max(1, roundTo(baseDamage - armorProtection, 1)) // Minimum 1 damage
}

/**
 * Calculate tome damage with elemental modifiers
 */
export function calculateTomeDamage(
  tomeDamage: number,
  tomeElement: string,
  mobWeakness: string,
  mobResistance: string
): { damage: number; wasEffective: boolean; wasResisted: boolean } {
  let damage = tomeDamage
  let wasEffective = false
  let wasResisted = false

  if (tomeElement === mobWeakness) {
    damage *= 1.25
    wasEffective = true
  } else if (tomeElement === mobResistance) {
    damage *= 0.5
    wasResisted = true
  }

  return { damage: roundTo(damage, 1), wasEffective, wasResisted }
}

// ============================================================================
// PLAYER ATTACKS
// ============================================================================

/**
 * Execute a player weapon attack against a mob
 */
export function playerWeaponAttack(
  character: Character,
  mob: Mob,
  weapon: Weapon | null,
  mobArmor: Armor | null
): AttackResult {
  const hit = playerAttackHits(character.dexterity, mob.dexterity)

  if (!hit) {
    return {
      hit: false,
      damage: 0,
      bodyPart: null,
      weaponBroke: false,
      armorBroke: false,
      message: `You swing at the ${mob.name} but miss!`,
    }
  }

  // Calculate damage
  const armorProtection = mobArmor ? mobArmor.protection : 0
  let damage: number
  let weaponBroke = false
  let armorBroke = false

  if (weapon) {
    damage = calculateWeaponDamage(weapon.damage, character.strength, armorProtection)
    weaponBroke = damageWeapon(weapon)
  } else {
    damage = calculatePunchDamage(character.strength, armorProtection)
  }

  // Damage mob armor
  if (mobArmor && damage > 0) {
    armorBroke = damageArmor(mobArmor)
  }

  // Select body part and apply damage
  const bodyPart = selectBodyPart()
  applyDamageToMob(mob, damage, bodyPart)

  // Generate message
  const weaponName = weapon ? weapon.name : 'fists'
  let message = `You strike the ${mob.name}'s ${getBodyPartName(bodyPart)} with your ${weaponName} for ${damage} damage!`

  if (weaponBroke) {
    message += ` Your ${weapon!.name} breaks!`
  }

  return { hit: true, damage, bodyPart, weaponBroke, armorBroke, message }
}

/**
 * Execute a player tome attack against a mob
 */
export function playerTomeAttack(
  character: Character,
  mob: Mob,
  tome: Tome
): TomeAttackResult {
  const hit = tomeAttackHits(character.dexterity, mob.dexterity)

  if (!hit) {
    // Tome still loses durability on miss
    const tomeBroke = damageTome(tome)
    return {
      hit: false,
      damage: 0,
      tomeBroke,
      wasEffective: false,
      wasResisted: false,
      message: `You cast ${tome.element} at the ${mob.name} but it dissipates harmlessly!${tomeBroke ? ` Your ${tome.name} crumbles to dust!` : ''}`,
    }
  }

  // Calculate damage with elemental modifiers
  const { damage, wasEffective, wasResisted } = calculateTomeDamage(
    tome.damage,
    tome.element,
    mob.weakness,
    mob.resistance
  )

  // Apply damage directly to health (tomes bypass body parts)
  mob.currentHealth = Math.max(0, mob.currentHealth - damage)

  // Damage tome
  const tomeBroke = damageTome(tome)

  // Generate message
  let message = `You cast ${tome.element} at the ${mob.name} for ${damage} damage!`
  if (wasEffective) {
    message += ` It's super effective!`
  } else if (wasResisted) {
    message += ` It's not very effective...`
  }
  if (tomeBroke) {
    message += ` Your ${tome.name} crumbles to dust!`
  }

  return { hit: true, damage, tomeBroke, wasEffective, wasResisted, message }
}

// ============================================================================
// MOB ATTACKS
// ============================================================================

/**
 * Execute a mob attack against the player
 */
export function mobAttack(
  mob: Mob,
  character: Character,
  mobWeapon: Weapon | null,
  playerArmor: Armor | null,
  isDefending: boolean
): AttackResult {
  const hit = mobAttackHits(character.dexterity, mob.dexterity, isDefending)

  if (!hit) {
    const defenseText = isDefending ? ' Your defensive stance helped you dodge!' : ''
    return {
      hit: false,
      damage: 0,
      bodyPart: null,
      weaponBroke: false,
      armorBroke: false,
      message: `The ${mob.name} attacks but misses!${defenseText}`,
    }
  }

  // Calculate damage
  const armorProtection = playerArmor ? playerArmor.protection : 0
  let damage: number
  let weaponBroke = false
  let armorBroke = false

  if (mobWeapon) {
    damage = calculateWeaponDamage(mobWeapon.damage, mob.strength, armorProtection)
    weaponBroke = damageWeapon(mobWeapon)
  } else {
    damage = calculatePunchDamage(mob.strength, armorProtection)
  }

  // Damage player armor
  if (playerArmor && damage > 0) {
    armorBroke = damageArmor(playerArmor)
  }

  // Select body part and apply damage
  const bodyPart = selectBodyPart()
  applyDamageToCharacter(character, damage, bodyPart)

  // Generate message
  const weaponName = mobWeapon ? `its ${mobWeapon.shortName}` : 'its claws'
  let message = `The ${mob.name} strikes your ${getBodyPartName(bodyPart)} with ${weaponName} for ${damage} damage!`

  if (armorBroke) {
    message += ` Your armor breaks!`
  }

  return { hit: true, damage, bodyPart, weaponBroke, armorBroke, message }
}

// ============================================================================
// DAMAGE APPLICATION
// ============================================================================

/**
 * Apply damage to a mob's body part
 */
function applyDamageToMob(mob: Mob, damage: number, bodyPart: BodyPart): void {
  mob.currentHealth = Math.max(0, mob.currentHealth - damage)
  mob.wounds[bodyPart] += damage
}

/**
 * Apply damage to a character's body part
 */
function applyDamageToCharacter(character: Character, damage: number, bodyPart: BodyPart): void {
  character.currentHealth = Math.max(0, character.currentHealth - damage)
  character.wounds[bodyPart] += damage
}

// ============================================================================
// TURN PROCESSING
// ============================================================================

/**
 * Process end of turn effects (bleeding, healing, etc.)
 */
export function processTurnEnd(
  character: Character,
  mob: Mob | null
): { playerBleedDamage: number; mobBleedDamage: number; messages: string[] } {
  const messages: string[] = []
  let playerBleedDamage = 0
  let mobBleedDamage = 0

  // Auto heal character
  autoHeal(character)

  // Process player bleeding
  playerBleedDamage = calculateBleeding(character)
  if (playerBleedDamage > 0) {
    character.currentHealth = Math.max(0, character.currentHealth - playerBleedDamage)
    messages.push(`You bleed for ${roundTo(playerBleedDamage, 1)} damage.`)
  }

  // Reduce player wounds over time
  healWounds(character)

  // Process mob bleeding if in combat
  if (mob && !mob.isCorpse) {
    mobBleedDamage = calculateMobBleeding(mob)
    if (mobBleedDamage > 0) {
      mob.currentHealth = Math.max(0, mob.currentHealth - mobBleedDamage)
      messages.push(`The ${mob.name} bleeds for ${roundTo(mobBleedDamage, 1)} damage.`)
    }

    // Reduce mob wounds over time
    const offset = mob.totalHealth * 0.05
    mob.wounds.head = Math.max(0, mob.wounds.head - offset)
    mob.wounds.torso = Math.max(0, mob.wounds.torso - offset)
    mob.wounds.leftArm = Math.max(0, mob.wounds.leftArm - offset)
    mob.wounds.rightArm = Math.max(0, mob.wounds.rightArm - offset)
    mob.wounds.leftLeg = Math.max(0, mob.wounds.leftLeg - offset)
    mob.wounds.rightLeg = Math.max(0, mob.wounds.rightLeg - offset)
  }

  // Increment turn counter
  character.turns++

  return { playerBleedDamage, mobBleedDamage, messages }
}

/**
 * Check and handle mob death
 * Returns experience gained
 */
export function checkMobDeath(mob: Mob): { died: boolean; experience: number } {
  if (mob.currentHealth <= 0 && !mob.isCorpse) {
    mob.isCorpse = true
    return { died: true, experience: mob.experience }
  }
  return { died: false, experience: 0 }
}

/**
 * Check and handle player death
 */
export function checkPlayerDeath(character: Character): boolean {
  if (character.currentHealth <= 0) {
    killCharacter(character)
    return true
  }
  return false
}

// ============================================================================
// BATTLE LOG HELPERS
// ============================================================================

/**
 * Create a battle log entry
 */
export function createBattleLogEntry(
  turn: number,
  type: BattleLogEntry['type'],
  message: string
): BattleLogEntry {
  return {
    id: generateId(),
    turn,
    type,
    message,
    timestamp: Date.now(),
  }
}

// ============================================================================
// FULL COMBAT TURN
// ============================================================================

/**
 * Execute a full combat turn (player action, mob action, end of turn)
 */
export function executeCombatTurn(
  action: 'attack' | 'defend' | 'tome',
  character: Character,
  mob: Mob,
  playerWeapon: Weapon | null,
  playerArmor: Armor | null,
  playerTome: Tome | null,
  mobWeapon: Weapon | null,
  mobArmor: Armor | null
): TurnResult {
  const messages: string[] = []
  let playerAttackResult: AttackResult | TomeAttackResult | null = null
  let mobAttackResult: AttackResult | null = null
  let experienceGained = 0
  let leveledUp = false
  const isDefending = action === 'defend'

  // Player action
  if (action === 'attack') {
    playerAttackResult = playerWeaponAttack(character, mob, playerWeapon, mobArmor)
    messages.push(playerAttackResult.message)
  } else if (action === 'tome' && playerTome) {
    playerAttackResult = playerTomeAttack(character, mob, playerTome)
    messages.push(playerAttackResult.message)
  } else if (action === 'defend') {
    messages.push('You steel yourself for the incoming attack.')
  }

  // Check if mob died from player attack
  const mobDeathCheck = checkMobDeath(mob)
  if (mobDeathCheck.died) {
    messages.push(`The ${mob.name} collapses!`)
    experienceGained = mobDeathCheck.experience
    leveledUp = addExperience(character, experienceGained)
    if (leveledUp) {
      messages.push(`You leveled up! You are now level ${character.level}.`)
    }
  }

  // Mob attacks (if still alive)
  if (!mob.isCorpse) {
    mobAttackResult = mobAttack(mob, character, mobWeapon, playerArmor, isDefending)
    messages.push(mobAttackResult.message)
  }

  // Process end of turn
  const turnEnd = processTurnEnd(character, mob.isCorpse ? null : mob)
  messages.push(...turnEnd.messages)

  // Final death checks
  const playerDied = checkPlayerDeath(character)

  if (!mobDeathCheck.died) {
    const finalMobDeath = checkMobDeath(mob)
    if (finalMobDeath.died) {
      messages.push(`The ${mob.name} bleeds out and dies!`)
      experienceGained = finalMobDeath.experience
      leveledUp = addExperience(character, experienceGained)
      if (leveledUp) {
        messages.push(`You leveled up! You are now level ${character.level}.`)
      }
    }
  }

  if (playerDied) {
    messages.push('You have died.')
  }

  return {
    playerAttackResult,
    mobAttackResult,
    playerBleedDamage: turnEnd.playerBleedDamage,
    mobBleedDamage: turnEnd.mobBleedDamage,
    playerDied,
    mobDied: mob.isCorpse,
    experienceGained,
    leveledUp,
    messages,
  }
}

/**
 * Execute a non-combat turn (moving, picking up items, etc.)
 */
export function executeExplorationTurn(character: Character): { messages: string[] } {
  const messages: string[] = []

  // Process end of turn effects
  const turnEnd = processTurnEnd(character, null)
  messages.push(...turnEnd.messages)

  // Check death from bleeding
  if (checkPlayerDeath(character)) {
    messages.push('You have bled out and died.')
  }

  return { messages }
}
