// Character system for Textlike
// Based on original PHP: characterfunctions.php

import type { Character, BodyWounds, Weapon, Armor, Tome, Consumable } from './types'
import { generateId, clamp, roundTo } from './utils'

// ============================================================================
// CHARACTER CREATION
// ============================================================================

/**
 * Create a new character with default stats
 */
export function createCharacter(name: string): Character {
  const baseStats = {
    strength: 10,
    stamina: 10,
    dexterity: 10,
    intelligence: 10,
    luck: 10,
  }

  const totalHealth = calculateTotalHealth(baseStats.stamina)
  const totalCarry = calculateTotalCarry(baseStats.strength)

  return {
    id: generateId(),
    name,
    createdAt: Date.now(),

    // Level & Experience
    level: 1,
    experience: 0,
    nextLevelExp: 100, // First level up at 100 exp
    lastLevelExp: 0,
    availablePoints: 0,
    turns: 0,

    // Primary Attributes
    ...baseStats,

    // Health
    totalHealth,
    currentHealth: totalHealth,

    // Carry capacity
    totalCarry,

    // Location
    currentRoomId: null,

    // Equipment
    equippedWeaponId: null,
    equippedArmorId: null,
    equippedTomeId: null,

    // Body wounds
    wounds: createEmptyWounds(),

    // Combat state
    attackingMobId: null,
    isDead: false,
  }
}

// ============================================================================
// STAT CALCULATIONS
// ============================================================================

/**
 * Calculate total health from stamina
 * Formula: 150 + ((stamina - 10) * 30)
 */
export function calculateTotalHealth(stamina: number): number {
  return 150 + (stamina - 10) * 30
}

/**
 * Calculate total carry capacity from strength
 * Formula: 40 + ((strength - 10) * 10)
 */
export function calculateTotalCarry(strength: number): number {
  const offset = Math.max(strength - 10, 0)
  return 40 + offset * 10
}

/**
 * Refresh a character's derived stats (health, carry) after stat changes
 */
export function refreshCharacterStats(character: Character): void {
  character.totalHealth = calculateTotalHealth(character.stamina)
  character.totalCarry = calculateTotalCarry(character.strength)

  // Cap current health at total
  character.currentHealth = Math.min(character.currentHealth, character.totalHealth)
}

// ============================================================================
// EXPERIENCE & LEVELING
// ============================================================================

/**
 * Calculate experience needed to reach a specific level
 * Uses 1.5x multiplier per level (matching PHP 0.8.1)
 */
export function calculateExperienceForLevel(level: number): number {
  let expLast = 0
  let expNeed = 100

  for (let i = 1; i < level; i++) {
    const expNeeded = (expNeed - expLast) * 1.5
    expLast = expNeed
    expNeed = expNeeded + expLast
  }

  return Math.round(expNeed)
}

/**
 * Add experience to a character and check for level up
 * Returns true if character leveled up
 */
export function addExperience(character: Character, amount: number): boolean {
  character.experience += amount

  if (character.experience >= character.nextLevelExp) {
    return levelUp(character)
  }

  return false
}

/**
 * Level up a character
 * Returns true if successful
 */
export function levelUp(character: Character): boolean {
  if (character.experience < character.nextLevelExp) {
    return false
  }

  character.level++
  character.availablePoints += 1
  character.lastLevelExp = character.nextLevelExp
  character.nextLevelExp = calculateExperienceForLevel(character.level + 1)

  return true
}

/**
 * Spend an available stat point
 */
export function spendStatPoint(
  character: Character,
  stat: 'strength' | 'stamina' | 'dexterity' | 'intelligence' | 'luck'
): boolean {
  if (character.availablePoints <= 0) {
    return false
  }

  character[stat]++
  character.availablePoints--

  // Refresh derived stats
  refreshCharacterStats(character)

  return true
}

/**
 * Get experience progress percentage (0-99)
 */
export function getExperienceProgress(character: Character): number {
  const totalDiff = character.nextLevelExp - character.lastLevelExp
  const currentDiff = character.nextLevelExp - character.experience
  const progress = Math.round(100 - (currentDiff / totalDiff) * 100)
  return clamp(progress, 0, 99)
}

// ============================================================================
// WOUNDS & BLEEDING
// ============================================================================

/**
 * Create empty wounds object
 */
export function createEmptyWounds(): BodyWounds {
  return {
    head: 0,
    torso: 0,
    leftArm: 0,
    rightArm: 0,
    leftLeg: 0,
    rightLeg: 0,
  }
}

/**
 * Calculate total bleeding damage per turn for character
 * PHP behavior: Uses round() on maxBleed, offset, and each body part damage individually
 */
export function calculateBleeding(character: Character): number {
  const maxBleed = Math.round(character.totalHealth * 0.1)
  const offset = Math.round(character.totalHealth * 0.05)

  // Apply offset to wounds before calculation (PHP: if wound <= offset, set to 0, else subtract offset)
  const head = character.wounds.head <= offset ? 0 : character.wounds.head - offset
  const torso = character.wounds.torso <= offset ? 0 : character.wounds.torso - offset
  const leftArm = character.wounds.leftArm <= offset ? 0 : character.wounds.leftArm - offset
  const rightArm = character.wounds.rightArm <= offset ? 0 : character.wounds.rightArm - offset
  const leftLeg = character.wounds.leftLeg <= offset ? 0 : character.wounds.leftLeg - offset
  const rightLeg = character.wounds.rightLeg <= offset ? 0 : character.wounds.rightLeg - offset

  // Calculate bleed damage per location (PHP rounds each individually)
  const headDamage = Math.round((head / character.totalHealth) * (maxBleed * 0.4))
  const torsoDamage = Math.round((torso / character.totalHealth) * (maxBleed * 0.3))
  const leftArmDamage = Math.round((leftArm / character.totalHealth) * (maxBleed * 0.2))
  const rightArmDamage = Math.round((rightArm / character.totalHealth) * (maxBleed * 0.2))
  const leftLegDamage = Math.round((leftLeg / character.totalHealth) * (maxBleed * 0.1))
  const rightLegDamage = Math.round((rightLeg / character.totalHealth) * (maxBleed * 0.1))

  const total = headDamage + torsoDamage + leftArmDamage + rightArmDamage + leftLegDamage + rightLegDamage

  // Cap at maxBleed
  return total > maxBleed ? maxBleed : total
}

/**
 * Apply natural healing to wound counters (reduce over time)
 */
export function healWounds(character: Character): void {
  const offset = character.totalHealth * 0.05

  character.wounds.head = Math.max(0, character.wounds.head - offset)
  character.wounds.torso = Math.max(0, character.wounds.torso - offset)
  character.wounds.leftArm = Math.max(0, character.wounds.leftArm - offset)
  character.wounds.rightArm = Math.max(0, character.wounds.rightArm - offset)
  character.wounds.leftLeg = Math.max(0, character.wounds.leftLeg - offset)
  character.wounds.rightLeg = Math.max(0, character.wounds.rightLeg - offset)
}

/**
 * Get total wounds (bleeding indicator)
 */
export function getTotalWounds(character: Character): number {
  const w = character.wounds
  return Math.round(w.head + w.torso + w.leftArm + w.rightArm + w.leftLeg + w.rightLeg)
}

/**
 * Get bleeding severity description
 */
export function getBleedingSeverityText(bleedPercent: number): string {
  if (bleedPercent > 0.35) return 'certain death from bleeding'
  if (bleedPercent > 0.25) return 'grievous wounds'
  if (bleedPercent > 0.10) return 'quite a lot'
  if (bleedPercent > 0.07) return 'quite a bit'
  if (bleedPercent > 0.04) return 'a good amount'
  if (bleedPercent > 0.02) return 'a small amount'
  if (bleedPercent > 0) return 'a tiny amount'
  return 'nothing'
}

// ============================================================================
// INVENTORY & EQUIPMENT
// ============================================================================

/**
 * Calculate current carried weight
 */
export function calculateCurrentWeight(
  character: Character,
  weapons: Map<string, Weapon>,
  armors: Map<string, Armor>,
  tomes: Map<string, Tome>
): number {
  let total = 0

  // Add weights of all items owned by character
  for (const weapon of weapons.values()) {
    if (weapon.location.type === 'character' && weapon.location.id === character.id) {
      total += weapon.weight
    }
  }

  for (const armor of armors.values()) {
    if (armor.location.type === 'character' && armor.location.id === character.id) {
      total += armor.weight
    }
  }

  for (const tome of tomes.values()) {
    if (tome.location.type === 'character' && tome.location.id === character.id) {
      total += tome.weight
    }
  }

  return roundTo(total, 1)
}

/**
 * Check if character is encumbered (carrying too much)
 */
export function isEncumbered(
  character: Character,
  weapons: Map<string, Weapon>,
  armors: Map<string, Armor>,
  tomes: Map<string, Tome>
): boolean {
  const currentWeight = calculateCurrentWeight(character, weapons, armors, tomes)
  return currentWeight > character.totalCarry
}

/**
 * Get inventory items for a character
 */
export function getCharacterInventory(
  characterId: string,
  weapons: Map<string, Weapon>,
  armors: Map<string, Armor>,
  tomes: Map<string, Tome>,
  consumables: Map<string, Consumable>
): {
  weapons: Weapon[]
  armors: Armor[]
  tomes: Tome[]
  consumables: Consumable[]
} {
  return {
    weapons: Array.from(weapons.values()).filter(
      w => w.location.type === 'character' && w.location.id === characterId
    ),
    armors: Array.from(armors.values()).filter(
      a => a.location.type === 'character' && a.location.id === characterId
    ),
    tomes: Array.from(tomes.values()).filter(
      t => t.location.type === 'character' && t.location.id === characterId
    ),
    consumables: Array.from(consumables.values()).filter(
      c => c.location.type === 'character' && c.location.id === characterId
    ),
  }
}

/**
 * Count bandages in character inventory
 */
export function countBandages(characterId: string, consumables: Map<string, Consumable>): number {
  return Array.from(consumables.values()).filter(
    c => c.location.type === 'character' && c.location.id === characterId && c.kind === 'Bandages'
  ).length
}

/**
 * Count apples in character inventory
 */
export function countApples(characterId: string, consumables: Map<string, Consumable>): number {
  return Array.from(consumables.values()).filter(
    c => c.location.type === 'character' && c.location.id === characterId && c.kind === 'Apples'
  ).length
}

// ============================================================================
// SCORE CALCULATION
// ============================================================================

/**
 * Calculate score for a character
 * Formula: (experience / (turns / 2)) * 100
 */
export function calculateScore(character: Character): number {
  if (character.turns === 0) return 0
  return Math.round((character.experience / (character.turns / 2)) * 100)
}

// ============================================================================
// DEATH
// ============================================================================

/**
 * Check if character is dead
 */
export function isCharacterDead(character: Character): boolean {
  return character.currentHealth <= 0 || character.isDead
}

/**
 * Mark character as dead
 */
export function killCharacter(character: Character): void {
  character.isDead = true
  character.currentHealth = 0
}

// ============================================================================
// HEALING
// ============================================================================

/**
 * Auto-heal character by reducing each body part wound by 1
 * Matches PHP 0.8.1 behavior where auto_heal reduces each body part damage
 */
export function autoHeal(character: Character): void {
  // Reduce each body part wound by 1 (matching PHP auto_heal)
  character.wounds.head = Math.max(0, character.wounds.head - 1)
  character.wounds.torso = Math.max(0, character.wounds.torso - 1)
  character.wounds.leftArm = Math.max(0, character.wounds.leftArm - 1)
  character.wounds.rightArm = Math.max(0, character.wounds.rightArm - 1)
  character.wounds.leftLeg = Math.max(0, character.wounds.leftLeg - 1)
  character.wounds.rightLeg = Math.max(0, character.wounds.rightLeg - 1)
}

/**
 * Heal character with apples - restores to full health
 * PHP behavior: heal_amount = total_health (heals completely)
 */
export function healWithApple(character: Character): number {
  const healAmount = character.totalHealth - character.currentHealth
  character.currentHealth = character.totalHealth
  return healAmount
}

/**
 * Use bandages to completely heal all wounds
 * PHP behavior: Sets all body part damage to 0
 */
export function useBandages(character: Character): void {
  character.wounds.head = 0
  character.wounds.torso = 0
  character.wounds.leftArm = 0
  character.wounds.rightArm = 0
  character.wounds.leftLeg = 0
  character.wounds.rightLeg = 0
}
