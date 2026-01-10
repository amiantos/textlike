// Dungeon generation system for Textlike
// Based on original PHP: functions.php, roomdesccreator.php

import type { Room, Chest, Direction } from './types'
import { generateId, randomChoice, chance } from './utils'

// ============================================================================
// ROOM DESCRIPTION GENERATION
// Matches PHP roomdesccreator.php exactly: picks ONE category, then ONE text
// ============================================================================

// Intro statements (PHP uses rand 1-3, so 3 options)
const ROOM_DESC_INTRO = [
  'The air here is hot, and you can feel it in your lungs; whatever it is floating in the air, it is inside you.',
  'You see your breath in the air before you even have a chance to shiver. You try rubbing the cold out of your arms, but it\'s already cut you to the bone.',
  'This room is filled with a thick cloud of swirling sand, being pushed by a wind you can\'t find the source of. You have to put a cloth over your mouth to breathe, and squint at your surroundings through pinched fingers.',
]

// Floor descriptions (PHP uses rand 0-3, so 4 options)
const ROOM_DESC_FLOOR = [
  'The ground under your feet feels moist and sticky. You think that falling down here would turn into a messy ordeal.',
  'With every step you take, cockroaches crunch under your feet; countless others race away into the dark corners.',
  'If you stand in place too long, the sand all over the floor begins to swallow your feet. If you keep moving, you should be fine.',
  'The ground under your feet is slick with a clear substance. It glistens in what little light there is. It appears to be the trails of several giant snails, but how they came into, and slipped out of, the room isn\'t apparent to you.',
]

// Wall descriptions (PHP uses rand 0-3, so 4 options)
const ROOM_DESC_WALL = [
  'Adorning the walls of this room are enormous engravings, depicting the fallen heroes of civilizations long forgotten.',
  'Hanging on one of the walls of this room is large painting of a horse. The horse is screaming.',
  'The walls of this room are covered in a beautiful mosaic. The tiles of turquoise, amethyst, and quartz form an elaborate scene of a bustling market place in the city, which stands in sharp contrast to the desolation around you. When you look closer, however, you see that the wears on display in the market are actually desiccated pieces of dismembered corpses.',
  'Before you, large blood red tapestries hang from the walls, adorned with a strange crest you\'ve never seen before.',
]

// Ceiling/overhead descriptions (PHP uses rand 0-3, so 4 options)
const ROOM_DESC_CEILING = [
  'Suspended over your head by chains, the bodies of the long dead and forgotten sway, as if jostled by someone else who was here just before you.',
  'At first you think the ceiling is pulsating, but you realize that it is home to hundreds of bats. You decide it\'s best not to disturb them.',
  'From above you can hear the sound of something slowly chewing on something else, but the ceiling is too high and all you can see is darkness. Whatever it making that sound, you hope it\'s friendly, or at least totally distracted already.',
  'The sound of chains clinking together fills the chamber as you move through it, pushing away the variety of hooks hanging down from above.',
]

// Special features (PHP uses rand 0-3, so 4 options)
const ROOM_DESC_SPECIAL = [
  'Coming from a hole in the base of one of the walls, a small stream cuts its way through the center of this room. The pebbles at the bottom of the stream have been worn smooth by decades of tumbling water.',
  'Though you can\'t fathom why, a small crack in the ceiling exposes a little of what must be sunlight. You can feel the warmth of it on your face; taking a moment to enjoy it, you remember something you had forgotten long ago.',
  'You only saw it for a moment when you entered, but you swear there was a faery hovering in the middle of the room, only to vanish into a crack in the wall when it noticed you come in.',
  'A stream of water falls from a hole in the ceiling and into a hole in the floor. Both holes are too precise to be natural. Where is this water going? Where is it from?',
]

// All room description categories for random selection
const ROOM_DESC_CATEGORIES = [
  ROOM_DESC_INTRO,
  ROOM_DESC_FLOOR,
  ROOM_DESC_WALL,
  ROOM_DESC_CEILING,
  ROOM_DESC_SPECIAL,
]

/**
 * Generate a random room description
 * PHP behavior: picks ONE category (0-4), then picks ONE text from that category
 */
export function generateRoomDescription(): string {
  const category = randomChoice(ROOM_DESC_CATEGORIES)
  return randomChoice(category)
}

/**
 * Generate the intro statement for the very first room
 * Matches PHP load_intro_statement() exactly
 */
export function generateIntroStatement(): string {
  const statements = [
    'You woke up. For a second you forgot why you were here, or where here even was, but then you remembered and a calmness washed over you.',
    'Welcome to the rest of your life. Maybe if you reach the bottom, you\'ll get to survive?',
    'Where are you? How did you get here? Wait... who are you? Maybe the better question is, what did you do to end up here?',
    'You sniff the air. There are hints of jasmine and bitter almond, or maybe that is just a memory you\'d rather forget.',
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
