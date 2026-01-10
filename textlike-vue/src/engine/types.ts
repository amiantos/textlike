// Textlike Game Types

// ============================================================================
// ENUMS & CONSTANTS
// ============================================================================

export const WEAPON_MATERIALS = ['Copper', 'Bronze', 'Iron', 'Steel'] as const
export type WeaponMaterial = (typeof WEAPON_MATERIALS)[number]

export const WEAPON_QUALITIES = ['Unbalanced', 'Vintage', 'Fine', 'Quality', 'Artisan'] as const
export type WeaponQuality = (typeof WEAPON_QUALITIES)[number]

export const WEAPON_TYPES = ['Sword', 'Axe'] as const
export type WeaponType = (typeof WEAPON_TYPES)[number]

export const ARMOR_MATERIALS = ['Leather', 'Chain', 'Plate', 'Dragon Scale'] as const
export type ArmorMaterial = (typeof ARMOR_MATERIALS)[number]

export const ARMOR_QUALITIES = ['Ruined', 'Vintage', 'Classic', 'Royal', 'Divine'] as const
export type ArmorQuality = (typeof ARMOR_QUALITIES)[number]

export const TOME_ELEMENTS = ['Frost', 'Flame', 'Rot', 'Unicorn'] as const
export type TomeElement = (typeof TOME_ELEMENTS)[number]

export const ITEM_KINDS = ['Bandages', 'Apples'] as const
export type ItemKind = (typeof ITEM_KINDS)[number]

export const MOB_KINDS = [
  'molestor', 'slenderman', 'demon', 'priest', 'heretic',
  'cultist', 'stranger', 'cyclops', 'doppleganger', 'golem',
  'imp', 'mothman', 'ogre', 'satyr', 'siren',
  'vampire', 'succubus', 'werewolf', 'hobbit', 'bystander'
] as const
export type MobKind = (typeof MOB_KINDS)[number]

export const MOB_DISPOSITIONS = [
  'demented', 'psychotic', 'heretical', 'deranged', 'drooling',
  'mumbling', 'tiny', 'barmy', 'batty', 'berserk',
  'bonkers', 'crazed', 'erratic', 'idiotic', 'insane',
  'kooky', 'nutty', 'schizo', 'unbalanced', 'unglued',
  'unhinged', 'naked', 'wacky', 'balanced', 'reasonable',
  'responsible', 'sensible', 'innocent', 'totally wacked out'
] as const
export type MobDisposition = (typeof MOB_DISPOSITIONS)[number]

export const ELEMENTAL_TYPES = ['Flame', 'Frost', 'Rot'] as const
export type ElementalType = (typeof ELEMENTAL_TYPES)[number]

export const BODY_PARTS = ['head', 'torso', 'leftArm', 'rightArm', 'leftLeg', 'rightLeg'] as const
export type BodyPart = (typeof BODY_PARTS)[number]

export const DIRECTIONS = ['north', 'south', 'east', 'west', 'up', 'down'] as const
export type Direction = (typeof DIRECTIONS)[number]

// ============================================================================
// CORE ENTITY INTERFACES
// ============================================================================

export interface Weapon {
  id: string
  name: string
  shortName: WeaponType
  level: number
  quality: WeaponQuality
  material: WeaponMaterial
  damage: number
  totalDurability: number
  currentDurability: number
  weight: number
  location: ItemLocation
}

export interface Armor {
  id: string
  name: string
  shortName: string
  level: number
  quality: ArmorQuality
  material: ArmorMaterial
  protection: number
  totalDurability: number
  currentDurability: number
  weight: number
  location: ItemLocation
}

export interface Tome {
  id: string
  name: string
  element: TomeElement
  level: number
  damage: number
  totalDurability: number
  currentDurability: number
  weight: number
  location: ItemLocation
}

export interface Consumable {
  id: string
  kind: ItemKind
  location: ItemLocation
}

// Where an item currently is
export interface ItemLocation {
  type: 'character' | 'mob' | 'room'
  id: string // character id, mob id, or room id
}

export interface BodyWounds {
  head: number
  torso: number
  leftArm: number
  rightArm: number
  leftLeg: number
  rightLeg: number
}

export interface Character {
  id: string
  name: string
  createdAt: number

  // Level & Experience
  level: number
  experience: number
  nextLevelExp: number
  lastLevelExp: number
  availablePoints: number
  turns: number

  // Primary Attributes (base = 10)
  strength: number
  stamina: number
  dexterity: number
  intelligence: number
  luck: number

  // Health
  totalHealth: number
  currentHealth: number

  // Carry capacity
  totalCarry: number

  // Location
  currentRoomId: string | null

  // Equipment (item IDs)
  equippedWeaponId: string | null
  equippedArmorId: string | null
  equippedTomeId: string | null

  // Body wounds (for bleeding)
  wounds: BodyWounds

  // Combat state
  attackingMobId: string | null
  isDead: boolean
}

export interface Mob {
  id: string
  kind: MobKind
  disposition: MobDisposition
  name: string // Full name: "disposition kind"
  isBoss: boolean

  level: number
  strength: number
  stamina: number
  dexterity: number
  intelligence: number
  luck: number

  totalHealth: number
  currentHealth: number

  // Equipment (item IDs)
  equippedWeaponId: string | null
  equippedArmorId: string | null

  // Body wounds
  wounds: BodyWounds

  // Elemental properties
  weakness: ElementalType
  resistance: ElementalType

  // Experience granted on kill
  experience: number

  // Location
  roomId: string
  isCorpse: boolean
}

export interface Room {
  id: string
  x: number
  y: number
  level: number // Dungeon depth (1-25 going up, 26+ is basement)

  // Exit connections (room IDs or null if no exit)
  exits: {
    north: string | null
    south: string | null
    east: string | null
    west: string | null
    up: string | null
    down: string | null
  }

  // Generation flags
  roomsGenerated: boolean
  mobsGenerated: boolean

  // Description
  description: string
}

export interface Chest {
  id: string
  roomId: string
  isSpecial: boolean // Special chests have better loot
  name: string
  isOpened: boolean
}

// ============================================================================
// COMBAT TYPES
// ============================================================================

export type CombatAction = 'attack' | 'defend' | 'tome' | 'bandage' | 'apple'

export interface BattleLogEntry {
  id: string
  turn: number
  type: CombatAction | 'mobAttack' | 'bleeding' | 'death' | 'info'
  message: string
  timestamp: number
}

// ============================================================================
// FLOOR STATE (for tracking boss/stairs per level)
// ============================================================================

export interface FloorState {
  level: number
  stairsCreated: boolean
  bossSpawned: boolean
  bossRoomId: string | null
  bossMobId: string | null
}

// ============================================================================
// GAME STATE
// ============================================================================

export interface GameState {
  // Core state
  character: Character | null
  rooms: Map<string, Room>
  mobs: Map<string, Mob>
  weapons: Map<string, Weapon>
  armors: Map<string, Armor>
  tomes: Map<string, Tome>
  consumables: Map<string, Consumable>
  chests: Map<string, Chest>

  // Floor state (boss/stairs tracking per level)
  floorStates: Map<number, FloorState>

  // Battle log
  battleLog: BattleLogEntry[]

  // High scores (local only)
  highScores: HighScore[]

  // Game state flags
  isInCombat: boolean
  gameStarted: boolean
}

export interface HighScore {
  id: string
  characterName: string
  score: number
  level: number
  floor: string
  date: number
}

// ============================================================================
// GENERATION CONFIG (for weighted random selection)
// ============================================================================

export interface MaterialConfig {
  name: string
  damageMultiplier: number
  durabilityMultiplier: number
  weightMultiplier: number
  dropWeight: number // Relative probability
}

export interface QualityConfig {
  name: string
  damageMultiplier: number
  durabilityMultiplier: number
  weightMultiplier: number
  dropWeight: number
}
