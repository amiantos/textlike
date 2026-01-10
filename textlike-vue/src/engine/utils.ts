// Utility functions for Textlike

/**
 * Generate a unique ID
 */
export function generateId(): string {
  return Math.random().toString(36).substring(2, 9) + Date.now().toString(36)
}

/**
 * Roll N six-sided dice and return the sum
 * This is the core randomization mechanic from the original game
 */
export function diceRoll(numDice: number): number {
  let total = 0
  for (let i = 0; i < numDice; i++) {
    total += Math.floor(Math.random() * 6) + 1
  }
  return total
}

/**
 * Random integer between min and max (inclusive)
 */
export function randInt(min: number, max: number): number {
  return Math.floor(Math.random() * (max - min + 1)) + min
}

/**
 * Random float between min and max
 */
export function randFloat(min: number, max: number): number {
  return Math.random() * (max - min) + min
}

/**
 * Pick a random element from an array
 */
export function randomChoice<T>(array: readonly T[]): T {
  return array[Math.floor(Math.random() * array.length)]!
}

/**
 * Weighted random selection
 * Items with higher weights are more likely to be selected
 */
export function weightedRandomChoice<T extends { dropWeight: number }>(items: T[]): T {
  const totalWeight = items.reduce((sum, item) => sum + item.dropWeight, 0)
  let random = Math.random() * totalWeight

  for (const item of items) {
    random -= item.dropWeight
    if (random <= 0) {
      return item
    }
  }

  // Fallback (should never reach here)
  return items[items.length - 1]!
}

/**
 * Check if a random event occurs given a probability (1 in N chance)
 */
export function chance(oneIn: number): boolean {
  return randInt(1, oneIn) === 1
}

/**
 * Clamp a value between min and max
 */
export function clamp(value: number, min: number, max: number): number {
  return Math.max(min, Math.min(max, value))
}

/**
 * Round to a specific number of decimal places
 */
export function roundTo(value: number, decimals: number): number {
  const factor = Math.pow(10, decimals)
  return Math.round(value * factor) / factor
}

/**
 * Capitalize first letter of each word
 */
export function titleCase(str: string): string {
  return str
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

/**
 * Format a number with commas for display
 */
export function formatNumber(num: number): string {
  return num.toLocaleString()
}

/**
 * Calculate percentage (0-100)
 */
export function percentage(current: number, total: number): number {
  if (total === 0) return 0
  return Math.round((current / total) * 100)
}

/**
 * Shuffle an array in place (Fisher-Yates)
 */
export function shuffle<T>(array: T[]): T[] {
  const result = [...array]
  for (let i = result.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1))
    const temp = result[i]!
    result[i] = result[j]!
    result[j] = temp
  }
  return result
}
