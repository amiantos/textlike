// Item generation system for Textlike
// Based on original PHP: item-functions.php

import type {
  Weapon,
  Armor,
  Tome,
  Consumable,
  WeaponMaterial,
  WeaponQuality,
  WeaponType,
  ArmorMaterial,
  ArmorQuality,
  TomeElement,
  ItemKind,
  ItemLocation,
  MaterialConfig,
  QualityConfig,
} from './types'
import { WEAPON_TYPES, ITEM_KINDS } from './types'
import { generateId, randInt, randomChoice, weightedRandomChoice, roundTo, chance } from './utils'

// ============================================================================
// WEAPON CONFIGURATION
// ============================================================================

const WEAPON_MATERIAL_CONFIG: MaterialConfig[] = [
  { name: 'Copper', damageMultiplier: 0.8, durabilityMultiplier: 0.8, weightMultiplier: 0.8, dropWeight: 40 },
  { name: 'Bronze', damageMultiplier: 0.9, durabilityMultiplier: 0.9, weightMultiplier: 1.1, dropWeight: 34 },
  { name: 'Iron', damageMultiplier: 1.0, durabilityMultiplier: 1.0, weightMultiplier: 1.2, dropWeight: 16 },
  { name: 'Steel', damageMultiplier: 1.1, durabilityMultiplier: 1.1, weightMultiplier: 0.9, dropWeight: 10 },
]

const WEAPON_QUALITY_CONFIG: QualityConfig[] = [
  { name: 'Unbalanced', damageMultiplier: 0.95, durabilityMultiplier: 0.8, weightMultiplier: 1.1, dropWeight: 20 },
  { name: 'Vintage', damageMultiplier: 0.975, durabilityMultiplier: 0.9, weightMultiplier: 1.1, dropWeight: 20 },
  { name: 'Fine', damageMultiplier: 1.0, durabilityMultiplier: 1.0, weightMultiplier: 1.0, dropWeight: 38 },
  { name: 'Quality', damageMultiplier: 1.025, durabilityMultiplier: 1.05, weightMultiplier: 0.9, dropWeight: 12 },
  { name: 'Artisan', damageMultiplier: 1.05, durabilityMultiplier: 1.1, weightMultiplier: 0.8, dropWeight: 10 },
]

// ============================================================================
// ARMOR CONFIGURATION
// ============================================================================

const ARMOR_MATERIAL_CONFIG: MaterialConfig[] = [
  { name: 'Leather', damageMultiplier: 0.9, durabilityMultiplier: 0.8, weightMultiplier: 0.9, dropWeight: 40 },
  { name: 'Chain', damageMultiplier: 1.0, durabilityMultiplier: 1.0, weightMultiplier: 1.0, dropWeight: 34 },
  { name: 'Plate', damageMultiplier: 1.1, durabilityMultiplier: 1.1, weightMultiplier: 1.1, dropWeight: 16 },
  { name: 'Dragon Scale', damageMultiplier: 1.2, durabilityMultiplier: 0.9, weightMultiplier: 0.8, dropWeight: 10 },
]

const ARMOR_QUALITY_CONFIG: QualityConfig[] = [
  { name: 'Ruined', damageMultiplier: 0.8, durabilityMultiplier: 0.8, weightMultiplier: 0.9, dropWeight: 20 },
  { name: 'Vintage', damageMultiplier: 0.9, durabilityMultiplier: 0.9, weightMultiplier: 1.0, dropWeight: 20 },
  { name: 'Classic', damageMultiplier: 1.0, durabilityMultiplier: 1.0, weightMultiplier: 1.0, dropWeight: 38 },
  { name: 'Royal', damageMultiplier: 1.1, durabilityMultiplier: 1.05, weightMultiplier: 0.9, dropWeight: 12 },
  { name: 'Divine', damageMultiplier: 1.2, durabilityMultiplier: 1.1, weightMultiplier: 0.8, dropWeight: 10 },
]

// ============================================================================
// TOME CONFIGURATION
// ============================================================================

const TOME_ELEMENT_CONFIG = [
  { name: 'Frost', dropWeight: 33 },
  { name: 'Flame', dropWeight: 33 },
  { name: 'Rot', dropWeight: 33 },
  { name: 'Unicorn', dropWeight: 2 }, // Rare legendary element
]

// ============================================================================
// WEAPON GENERATION
// ============================================================================

/**
 * Generate a random weapon at the specified level
 */
export function generateWeapon(level: number, location: ItemLocation): Weapon {
  // Base stats (from original: 20 + ((level - 1) * 1.5))
  const baseDamage = 20 + (level - 1) * 1.5
  const baseWeight = 8 + (level - 1) * 0.3
  const baseDurability = 45 + (level - 1) * 1

  // Select material and quality using weighted random
  const material = weightedRandomChoice(WEAPON_MATERIAL_CONFIG)
  const quality = weightedRandomChoice(WEAPON_QUALITY_CONFIG)

  // Select weapon type (50/50 sword vs axe)
  const weaponType = randomChoice(WEAPON_TYPES)

  // Apply multipliers
  const damage = roundTo(baseDamage * material.damageMultiplier * quality.damageMultiplier, 1)
  const durability = Math.round(baseDurability * material.durabilityMultiplier * quality.durabilityMultiplier)
  const weight = roundTo(baseWeight * material.weightMultiplier * quality.weightMultiplier, 1)

  // Generate name: "{Quality} {Material} {Type}"
  const name = `${quality.name} ${material.name} ${weaponType}`

  return {
    id: generateId(),
    name,
    shortName: weaponType as WeaponType,
    level,
    quality: quality.name as WeaponQuality,
    material: material.name as WeaponMaterial,
    damage,
    totalDurability: durability,
    currentDurability: durability,
    weight,
    location,
  }
}

// ============================================================================
// ARMOR GENERATION
// ============================================================================

/**
 * Generate a random armor at the specified level
 */
export function generateArmor(level: number, location: ItemLocation): Armor {
  // Base stats (from original: 10 + ((level - 1) * 1.1))
  const baseProtection = 10 + (level - 1) * 1.1
  const baseWeight = 10 + (level - 1) * 1
  const baseDurability = 55 + (level - 1) * 0.9

  // Select material and quality using weighted random
  const material = weightedRandomChoice(ARMOR_MATERIAL_CONFIG)
  const quality = weightedRandomChoice(ARMOR_QUALITY_CONFIG)

  // Apply multipliers (note: armor uses damageMultiplier for protection)
  const protection = roundTo(baseProtection * material.damageMultiplier * quality.damageMultiplier, 1)
  const durability = Math.round(baseDurability * material.durabilityMultiplier * quality.durabilityMultiplier)
  const weight = roundTo(baseWeight * material.weightMultiplier * quality.weightMultiplier, 1)

  // Generate name: "{Quality} {Material} Armor"
  const name = `${quality.name} ${material.name} Armor`
  const shortName = `${quality.name} ${material.name}`

  return {
    id: generateId(),
    name,
    shortName,
    level,
    quality: quality.name as ArmorQuality,
    material: material.name as ArmorMaterial,
    protection,
    totalDurability: durability,
    currentDurability: durability,
    weight,
    location,
  }
}

// ============================================================================
// TOME GENERATION
// ============================================================================

/**
 * Generate a random tome at the specified level
 */
export function generateTome(level: number, location: ItemLocation): Tome {
  // Base stats (from original: 25 + ((level - 1) * 1.5) + rand(1,5))
  const baseDamage = 25 + (level - 1) * 1.5 + randInt(1, 5)
  const baseWeight = 1 + (level - 1) * 0.2
  const baseDurability = 2 + (level - 1) * 0.05

  // Select element using weighted random
  const elementConfig = weightedRandomChoice(TOME_ELEMENT_CONFIG)
  const element = elementConfig.name as TomeElement

  // Unicorn tomes get bonus damage and durability
  let damage = roundTo(baseDamage, 1)
  let durability = Math.max(1, Math.round(baseDurability))
  const weight = roundTo(baseWeight, 1)

  if (element === 'Unicorn') {
    damage = roundTo(damage * 1.5, 1)
    durability = Math.round(durability * 1.5)
  }

  const name = `Tome of ${element}`

  return {
    id: generateId(),
    name,
    element,
    level,
    damage,
    totalDurability: durability,
    currentDurability: durability,
    weight,
    location,
  }
}

// ============================================================================
// CONSUMABLE GENERATION
// ============================================================================

/**
 * Generate a random consumable item
 */
export function generateConsumable(location: ItemLocation, kind?: ItemKind): Consumable {
  return {
    id: generateId(),
    kind: kind ?? randomChoice(ITEM_KINDS),
    location,
  }
}

/**
 * Generate bandages specifically
 */
export function generateBandages(location: ItemLocation): Consumable {
  return generateConsumable(location, 'Bandages')
}

/**
 * Generate apples specifically
 */
export function generateApples(location: ItemLocation): Consumable {
  return generateConsumable(location, 'Apples')
}

// ============================================================================
// CHEST LOOT GENERATION
// ============================================================================

export interface ChestLoot {
  weapons: Weapon[]
  armors: Armor[]
  tomes: Tome[]
  consumables: Consumable[]
}

/**
 * Generate loot for a special chest (guaranteed good drops)
 */
export function generateSpecialChestLoot(level: number, roomId: string): ChestLoot {
  const location: ItemLocation = { type: 'room', id: roomId }

  return {
    weapons: [generateWeapon(level, location), generateWeapon(level, location), generateWeapon(level, location)],
    armors: [generateArmor(level, location), generateArmor(level, location), generateArmor(level, location)],
    tomes: [generateTome(level, location), generateTome(level, location), generateTome(level, location)],
    consumables: [
      generateConsumable(location),
      generateConsumable(location),
      chance(2) ? generateConsumable(location) : null,
    ].filter((c): c is Consumable => c !== null),
  }
}

/**
 * Generate loot for a normal chest
 */
export function generateNormalChestLoot(level: number, roomId: string): ChestLoot {
  const location: ItemLocation = { type: 'room', id: roomId }

  const weapons: Weapon[] = [generateWeapon(level, location)]
  const armors: Armor[] = [generateArmor(level, location)]
  const tomes: Tome[] = []
  const consumables: Consumable[] = []

  // Additional weapon chances
  if (chance(2)) weapons.push(generateWeapon(level, location))
  if (chance(40)) weapons.push(generateWeapon(level, location))

  // Additional armor chances
  if (chance(2)) armors.push(generateArmor(level, location))
  if (chance(40)) armors.push(generateArmor(level, location))

  // Tome chances
  if (chance(4)) tomes.push(generateTome(level, location))
  if (chance(7)) tomes.push(generateTome(level, location))
  if (chance(10)) tomes.push(generateTome(level, location))

  // Consumable chances
  if (chance(2)) consumables.push(generateConsumable(location))
  if (chance(2)) consumables.push(generateConsumable(location))
  if (chance(4)) consumables.push(generateConsumable(location))
  if (chance(10)) consumables.push(generateConsumable(location))

  return { weapons, armors, tomes, consumables }
}

// ============================================================================
// ITEM DAMAGE (DURABILITY)
// ============================================================================

/**
 * Reduce weapon durability by 1
 * Returns true if weapon broke
 */
export function damageWeapon(weapon: Weapon): boolean {
  weapon.currentDurability = Math.max(0, weapon.currentDurability - 1)
  return weapon.currentDurability === 0
}

/**
 * Reduce armor durability by 1
 * Returns true if armor broke
 */
export function damageArmor(armor: Armor): boolean {
  armor.currentDurability = Math.max(0, armor.currentDurability - 1)
  return armor.currentDurability === 0
}

/**
 * Reduce tome durability by 1
 * Returns true if tome broke
 */
export function damageTome(tome: Tome): boolean {
  tome.currentDurability = Math.max(0, tome.currentDurability - 1)
  return tome.currentDurability === 0
}

// ============================================================================
// ITEM UTILITIES
// ============================================================================

/**
 * Calculate total weight of items
 */
export function calculateTotalWeight(
  weapons: Weapon[],
  armors: Armor[],
  tomes: Tome[]
): number {
  let total = 0
  for (const w of weapons) total += w.weight
  for (const a of armors) total += a.weight
  for (const t of tomes) total += t.weight
  return roundTo(total, 1)
}

/**
 * Check if weapon is broken
 */
export function isWeaponBroken(weapon: Weapon): boolean {
  return weapon.currentDurability <= 0
}

/**
 * Check if armor is broken
 */
export function isArmorBroken(armor: Armor): boolean {
  return armor.currentDurability <= 0
}

/**
 * Check if tome is broken
 */
export function isTomeBroken(tome: Tome): boolean {
  return tome.currentDurability <= 0
}
