// Dungeon generation system for Textlike
// Based on original PHP: functions.php, roomdesccreator.php

import type { Room, Chest, Direction } from './types'
import { generateId, randomChoice, chance } from './utils'

// ============================================================================
// ROOM DESCRIPTION GENERATION
// ============================================================================

const INTRO_STATEMENTS = [
  'The room is hot and stuffy.',
  'The room feels cold and damp.',
  'The room is filled with a fine layer of sand.',
]

const FLOOR_DESCRIPTIONS = [
  'The floor is covered in old bones.',
  'The floor is surprisingly clean.',
  'The floor is slick with moisture.',
  'The floor is covered in strange markings.',
]

const WALL_DESCRIPTIONS = [
  'The walls are covered in moss.',
  'The walls have ancient runes carved into them.',
  'The walls are scorched black.',
  'The walls seem to pulse with an eerie light.',
]

const CEILING_DESCRIPTIONS = [
  'Stalactites hang from the ceiling.',
  'The ceiling is impossibly high.',
  'The ceiling drips with condensation.',
  'The ceiling is covered in cobwebs.',
]

const SPECIAL_FEATURES = [
  'A mysterious statue stands in the corner.',
  'An ancient altar dominates the center of the room.',
  'A dried-up fountain sits against one wall.',
  'Strange glowing crystals are embedded in the stone.',
]

/**
 * Generate a random room description
 */
export function generateRoomDescription(): string {
  const intro = randomChoice(INTRO_STATEMENTS)
  const floor = randomChoice(FLOOR_DESCRIPTIONS)
  const wall = randomChoice(WALL_DESCRIPTIONS)
  const ceiling = randomChoice(CEILING_DESCRIPTIONS)

  // 50% chance for a special feature
  const special = chance(2) ? ` ${randomChoice(SPECIAL_FEATURES)}` : ''

  return `${intro} ${floor} ${wall} ${ceiling}${special}`
}

/**
 * Generate the intro statement for the very first room
 */
export function generateIntroStatement(): string {
  const statements = [
    'You awaken in a dark, unfamiliar place. The air is thick with dust and the smell of age. You have no memory of how you got here.',
    'Your eyes slowly adjust to the dim light. You find yourself in what appears to be an ancient dungeon. The only way out is forward.',
    'The sound of dripping water echoes through the chamber. You stand up, brushing off the dirt, and look around. Adventure awaits.',
  ]
  return randomChoice(statements)
}

// ============================================================================
// ROOM GENERATION
// ============================================================================

/**
 * Create the initial room at coordinates (0, 0) on level 1
 */
export function createInitialRoom(): Room {
  return {
    id: generateId(),
    x: 0,
    y: 0,
    level: 1,
    exits: {
      north: null,
      south: null,
      east: null,
      west: null,
      up: null,
      down: null,
    },
    roomsGenerated: false,
    mobsGenerated: true, // First room has no mobs (safe room)
    description: generateRoomDescription(),
  }
}

/**
 * Create a new room at the specified coordinates and level
 */
export function createRoom(x: number, y: number, level: number): Room {
  return {
    id: generateId(),
    x,
    y,
    level,
    exits: {
      north: null,
      south: null,
      east: null,
      west: null,
      up: null,
      down: null,
    },
    roomsGenerated: false,
    mobsGenerated: false,
    description: generateRoomDescription(),
  }
}

/**
 * Get the opposite direction
 */
export function getOppositeDirection(direction: Direction): Direction {
  const opposites: Record<Direction, Direction> = {
    north: 'south',
    south: 'north',
    east: 'west',
    west: 'east',
    up: 'down',
    down: 'up',
  }
  return opposites[direction]
}

/**
 * Get coordinates for a room in the specified direction
 */
export function getCoordinatesInDirection(
  x: number,
  y: number,
  direction: Direction
): { x: number; y: number } {
  switch (direction) {
    case 'north':
      return { x: x + 1, y }
    case 'south':
      return { x: x - 1, y }
    case 'east':
      return { x, y: y + 1 }
    case 'west':
      return { x, y: y - 1 }
    default:
      return { x, y }
  }
}

/**
 * Link two rooms together
 */
export function linkRooms(
  fromRoom: Room,
  toRoom: Room,
  direction: Direction
): void {
  fromRoom.exits[direction] = toRoom.id
  toRoom.exits[getOppositeDirection(direction)] = fromRoom.id
}

/**
 * Generate exits for a room (creates new rooms or links to existing)
 * Returns array of newly created rooms
 */
export function generateExitsForRoom(
  room: Room,
  existingRooms: Map<string, Room>
): Room[] {
  const newRooms: Room[] = []
  const directions: Direction[] = ['north', 'south', 'east', 'west']

  // Count existing exits
  const existingExitCount = directions.filter(d => room.exits[d] !== null).length

  // Don't generate more than 4 exits total (horizontal)
  if (existingExitCount >= 4) {
    room.roomsGenerated = true
    return newRooms
  }

  // Track which directions we could create exits in
  const availableDirections: Direction[] = []

  for (const direction of directions) {
    // Skip if already has exit in this direction
    if (room.exits[direction] !== null) continue

    const coords = getCoordinatesInDirection(room.x, room.y, direction)

    // Check if room already exists at these coordinates
    const existingRoom = findRoomAtCoordinates(existingRooms, coords.x, coords.y, room.level)

    if (existingRoom) {
      // Link to existing room
      linkRooms(room, existingRoom, direction)
    } else {
      // 25% chance to create a new room (rand(0,4) == 1 in original)
      if (chance(4)) {
        const newRoom = createRoom(coords.x, coords.y, room.level)
        linkRooms(room, newRoom, direction)
        newRooms.push(newRoom)
      } else {
        // Track this as an available direction for fallback
        availableDirections.push(direction)
      }
    }
  }

  // Ensure at least one exit exists - if we have no exits after generation, force create one
  const totalExits = directions.filter(d => room.exits[d] !== null).length
  if (totalExits === 0 && availableDirections.length > 0) {
    const forcedDirection = randomChoice(availableDirections)
    const coords = getCoordinatesInDirection(room.x, room.y, forcedDirection)
    const newRoom = createRoom(coords.x, coords.y, room.level)
    linkRooms(room, newRoom, forcedDirection)
    newRooms.push(newRoom)
  }

  room.roomsGenerated = true
  return newRooms
}

/**
 * Find a room at specific coordinates
 */
export function findRoomAtCoordinates(
  rooms: Map<string, Room>,
  x: number,
  y: number,
  level: number
): Room | undefined {
  for (const room of rooms.values()) {
    if (room.x === x && room.y === y && room.level === level) {
      return room
    }
  }
  return undefined
}

/**
 * Check if all rooms on a level have been generated
 */
export function allRoomsGeneratedOnLevel(
  rooms: Map<string, Room>,
  level: number
): boolean {
  for (const room of rooms.values()) {
    if (room.level === level && !room.roomsGenerated) {
      return false
    }
  }
  return true
}

/**
 * Create stairs down to the next level
 * Returns the new room on the lower level
 */
export function createStairsDown(fromRoom: Room): Room {
  const newLevel = fromRoom.level + 1
  const newRoom = createRoom(fromRoom.x, fromRoom.y, newLevel)

  // Link the rooms vertically
  fromRoom.exits.down = newRoom.id
  newRoom.exits.up = fromRoom.id

  return newRoom
}

/**
 * Find a room suitable for placing stairs down
 * Prefers rooms with few exits
 */
export function findRoomForStairs(
  rooms: Map<string, Room>,
  level: number
): Room | undefined {
  const levelRooms = Array.from(rooms.values()).filter(r => r.level === level && r.exits.down === null)

  if (levelRooms.length === 0) return undefined

  // Sort by number of exits (prefer rooms with fewer exits)
  levelRooms.sort((a, b) => {
    const aExits = Object.values(a.exits).filter(e => e !== null).length
    const bExits = Object.values(b.exits).filter(e => e !== null).length
    return aExits - bExits
  })

  return levelRooms[0]
}

// ============================================================================
// FLOOR DISPLAY
// ============================================================================

/**
 * Get floor display string
 * Levels 1-25 count down from 25, levels 26+ become B1, B2, etc. (basement)
 */
export function getFloorDisplay(level: number): string {
  if (level <= 25) {
    return String(25 - (level - 1))
  } else {
    return `B${level - 25}`
  }
}

// ============================================================================
// CHEST GENERATION
// ============================================================================

const SPECIAL_CHEST_NAMES = [
  'Glowing Chest',
  'Vibrating Chest',
  'Ancient Chest',
  'Ornate Chest',
  'Mystical Chest',
]

const NORMAL_CHEST_NAMES = [
  'Wooden Chest',
  'Old Chest',
  'Dusty Chest',
  'Iron Chest',
]

/**
 * Generate a chest
 */
export function generateChest(roomId: string, isSpecial: boolean): Chest {
  const name = isSpecial
    ? randomChoice(SPECIAL_CHEST_NAMES)
    : randomChoice(NORMAL_CHEST_NAMES)

  return {
    id: generateId(),
    roomId,
    isSpecial,
    name,
    isOpened: false,
  }
}

/**
 * Should a chest spawn in this room?
 * 1 in 3 chance for normal rooms
 */
export function shouldSpawnChest(): boolean {
  return chance(3)
}

/**
 * Should this chest be special?
 * 1 in 100 chance
 */
export function shouldBeSpecialChest(): boolean {
  return chance(100)
}
