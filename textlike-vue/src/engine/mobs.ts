// Mob generation system for Textlike
// Based on original PHP: mobfunctions.php

import type {
  Mob,
  ElementalType,
  BodyWounds,
} from './types'
import {
  MOB_KINDS,
  MOB_DISPOSITIONS,
  ELEMENTAL_TYPES,
} from './types'
import { generateId, randInt, randomChoice, chance, titleCase } from './utils'
import { generateWeapon, generateArmor } from './items'

// ============================================================================
// MOB HEALTH CALCULATION
// ============================================================================

/**
 * Calculate mob total health from stamina
 * Formula: 50 + ((stamina - 10) * 11)
 */
export function calculateMobHealth(stamina: number): number {
  return 50 + (stamina - 10) * 11
}

// ============================================================================
// EXPERIENCE CALCULATION
// ============================================================================

/**
 * Calculate experience needed to reach a level
 * Uses exponential scaling with 1.1x multiplier per level
 */
function calculateExperienceForLevel(level: number): number {
  let expLast = 0
  let expNeed = 100

  for (let i = 1; i < level; i++) {
    const expNeeded = (expNeed - expLast) * 1.1
    expLast = expNeed
    expNeed = expNeeded + expLast
  }

  return Math.round(expNeed)
}

/**
 * Calculate experience reward for killing a mob
 * Based on level and how many enemies need to be defeated per level
 */
export function calculateMobExperience(level: number): number {
  const expNeeded = calculateExperienceForLevel(level)

  // Calculate ideal enemies per level (starts at 8, scales by 1.02x)
  let needed = 8
  for (let i = 1; i < level; i++) {
    needed *= 1.02
  }

  return Math.round(expNeeded / needed)
}

// ============================================================================
// STAT DISTRIBUTION
// ============================================================================

/**
 * Distribute stat points among strength, stamina, and dexterity
 * Points are distributed randomly one at a time
 */
function distributeStats(totalPoints: number): { strength: number; stamina: number; dexterity: number } {
  const stats = { strength: 10, stamina: 10, dexterity: 10 }
  const maxPerStat = Math.floor(totalPoints / 2.5) + 10 // Cap per stat

  let remaining = totalPoints
  while (remaining > 0) {
    // Pick a random stat to increase
    const roll = randInt(1, 3)
    if (roll === 1 && stats.strength < maxPerStat) {
      stats.strength++
      remaining--
    } else if (roll === 2 && stats.stamina < maxPerStat) {
      stats.stamina++
      remaining--
    } else if (roll === 3 && stats.dexterity < maxPerStat) {
      stats.dexterity++
      remaining--
    }
  }

  return stats
}

// ============================================================================
// ELEMENTAL ASSIGNMENT
// ============================================================================

/**
 * Assign weakness and resistance elements to a mob
 * Weakness and resistance must be different
 */
function assignElements(): { weakness: ElementalType; resistance: ElementalType } {
  const weakness = randomChoice(ELEMENTAL_TYPES)

  // Resistance must be different from weakness
  const availableResistances = ELEMENTAL_TYPES.filter(e => e !== weakness)
  const resistance = randomChoice(availableResistances)

  return { weakness, resistance }
}

// ============================================================================
// MOB GENERATION
// ============================================================================

/**
 * Generate a random mob at the specified room level
 */
export function generateMob(roomLevel: number, roomId: string, isBoss: boolean = false): Mob {
  // Calculate mob level with variance
  let level = roomLevel

  // Small variance (12.5% chance)
  if (chance(8)) {
    level += randInt(-1, 1)
  }

  // Critical spawns (2.5% chance each)
  if (chance(40)) level += 3
  if (chance(40)) level += 5

  // Boss mobs are 10 levels higher
  if (isBoss) {
    level = roomLevel + 10
  }

  // Minimum level 1
  level = Math.max(1, level)

  // Calculate total stat points: (level - 1) * 1
  const totalPoints = (level - 1) * 1
  const stats = distributeStats(totalPoints)

  // Calculate health
  const totalHealth = calculateMobHealth(stats.stamina)

  // Select kind and disposition
  const kind = randomChoice(MOB_KINDS)
  const disposition = randomChoice(MOB_DISPOSITIONS)

  // Generate full name
  const name = titleCase(`${disposition} ${kind}`)

  // Assign elements
  const { weakness, resistance } = assignElements()

  // Calculate experience reward
  const experience = calculateMobExperience(level)

  // Create the mob
  const mob: Mob = {
    id: generateId(),
    kind,
    disposition,
    name,
    isBoss,
    level,
    strength: stats.strength,
    stamina: stats.stamina,
    dexterity: stats.dexterity,
    intelligence: 10, // Not used in combat but kept for completeness
    luck: 10, // Not used in combat but kept for completeness
    totalHealth,
    currentHealth: totalHealth,
    equippedWeaponId: null,
    equippedArmorId: null,
    wounds: createEmptyWounds(),
    weakness,
    resistance,
    experience,
    roomId,
    isCorpse: false,
  }

  return mob
}

/**
 * Generate a mob with its equipment (weapon and armor)
 * Returns both the mob and its equipment items
 */
export function generateMobWithEquipment(
  roomLevel: number,
  roomId: string,
  isBoss: boolean = false
): {
  mob: Mob
  weapon: ReturnType<typeof generateWeapon>
  armor: ReturnType<typeof generateArmor>
} {
  const mob = generateMob(roomLevel, roomId, isBoss)

  // Generate weapon for the mob
  const weapon = generateWeapon(mob.level, { type: 'mob', id: mob.id })
  mob.equippedWeaponId = weapon.id

  // Generate armor for the mob
  const armor = generateArmor(mob.level, { type: 'mob', id: mob.id })
  mob.equippedArmorId = armor.id

  return { mob, weapon, armor }
}

// ============================================================================
// MOB UTILITIES
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
 * Calculate total bleeding for a mob
 * Mobs bleed more than players (60% max vs 10%)
 */
export function calculateMobBleeding(mob: Mob): number {
  const maxBleed = mob.totalHealth * 0.6

  // Body part weights for mobs
  const headDamage = (mob.wounds.head / mob.totalHealth) * (maxBleed * 0.5)
  const torsoDamage = (mob.wounds.torso / mob.totalHealth) * (maxBleed * 0.4)
  const leftArmDamage = (mob.wounds.leftArm / mob.totalHealth) * (maxBleed * 0.3)
  const rightArmDamage = (mob.wounds.rightArm / mob.totalHealth) * (maxBleed * 0.3)
  const leftLegDamage = (mob.wounds.leftLeg / mob.totalHealth) * (maxBleed * 0.2)
  const rightLegDamage = (mob.wounds.rightLeg / mob.totalHealth) * (maxBleed * 0.2)

  const total = headDamage + torsoDamage + leftArmDamage + rightArmDamage + leftLegDamage + rightLegDamage

  return Math.min(total, maxBleed)
}

/**
 * Check if mob is dead
 */
export function isMobDead(mob: Mob): boolean {
  return mob.currentHealth <= 0 || mob.isCorpse
}

/**
 * Get number of mobs to spawn in a room based on character level
 */
export function getMobSpawnCount(characterLevel: number): number {
  // Very rare chance (1 in 400) for empty room
  if (chance(400)) {
    return 0
  }

  if (characterLevel < 10) {
    return randInt(1, 2)
  } else if (characterLevel < 25) {
    return randInt(2, 3)
  } else {
    return randInt(2, 4)
  }
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
