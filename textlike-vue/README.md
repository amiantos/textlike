# Textlike Vue

A client-side Vue 3 recreation of **Textlike**, a text-based roguelike dungeon crawler originally built in PHP.

## Project Goal

**This project aims to be a 1:1 faithful recreation of the original PHP Textlike game.**

The original PHP version is located in the parent directory (`../`). When making changes or additions to this Vue version, always reference the original PHP source files to ensure accuracy. The goal is to preserve:

- **Exact game mechanics** - All formulas, probabilities, and calculations should match the original
- **Original UI/UX** - The visual layout, styling, and user flow should mirror the PHP version
- **Game feel** - Combat, exploration, and progression should feel identical to the original

### Why 1:1 Recreation?

The original game has carefully balanced mechanics that were refined over time. Rather than "improving" or "modernizing" the gameplay, we want to first achieve feature parity. Balance improvements or new features can be considered later, but only after the core game matches the original.

## Original PHP Reference Files

When implementing or fixing features, reference these original files:

| Feature | PHP Source Files |
|---------|-----------------|
| Database schema / Data structures | `../db/textlike.sql` |
| Character stats, health, leveling | `../characterfunctions.php` |
| Room/dungeon generation | `../functions.php`, `../roomdesccreator.php` |
| Mob generation, stats, AI | `../mobfunctions.php` |
| Weapon/armor/tome generation | `../item-functions.php` |
| Combat system, damage, hit rolls | `../attacking.php` |
| Bleeding, damage over time | `../bleeding.php` |
| Turn processing | `../endturn.php` |
| Main game display | `../index.php` |
| Action processing | `../processing.php` |
| Original CSS styling | `../desktop.css`, `../styles.css` |

## Key Game Mechanics (from original)

### Stats & Formulas
```
Player Health = 150 + ((stamina - 10) * 30)
Mob Health = 50 + ((stamina - 10) * 11)
Carry Capacity = 40 + ((strength - 10) * 10)
```

### Combat Hit Checks
```
Player Attack: roll 2d6 + (player_dex - mob_dex) >= roll 1d6
Mob Attack: roll 2d6 > roll 1d6 + (player_dex - mob_dex)
Tome Attack: roll 3d6 + (player_dex - mob_dex) >= roll 1d6
Defense: Swaps dice (player gets 2d6, mob gets 1d6)
```

### Damage Calculation
```
Weapon Damage = base_damage + (strength * 0.5)
Final Damage = max(0, weapon_damage - armor_protection)
Tome vs Weakness: damage * 1.25
Tome vs Resistance: damage * 0.5
```

### Item Generation
- **Weapons**: Materials (Copper/Bronze/Iron/Steel) + Quality (Unbalanced->Artisan)
- **Armor**: Materials (Leather/Chain/Plate/Dragon Scale) + Quality (Ruined->Divine)
- **Tomes**: Elements (Frost/Flame/Rot/Unicorn) with durability

### Bleeding System
- Body parts track accumulated damage
- Bleeding damage per turn based on wound severity and body part weights
- Natural healing reduces wounds over time

## Tech Stack

- **Vue 3** with Composition API (`<script setup>`)
- **TypeScript** for type safety
- **Pinia** for state management
- **Vite** for build tooling
- **localStorage** for game persistence

No backend required - runs entirely in the browser.

## Project Structure

```
src/
├── engine/                 # Pure TypeScript game logic (no Vue dependencies)
│   ├── types.ts           # All game interfaces and types
│   ├── utils.ts           # Dice rolls, random helpers
│   ├── items.ts           # Weapon/armor/tome generation
│   ├── mobs.ts            # Enemy generation
│   ├── dungeon.ts         # Room/map generation
│   ├── character.ts       # Player stats, leveling, inventory
│   └── combat.ts          # Combat resolution, damage, bleeding
│
├── stores/
│   └── gameStore.ts       # Pinia store with all game state + localStorage
│
├── components/
│   ├── MainMenu.vue       # New game / continue screen
│   ├── GameView.vue       # Main game container
│   ├── StatBar.vue        # Character stats display
│   ├── ActionBar.vue      # Items and combat actions
│   ├── RoomView.vue       # Room exploration view
│   ├── CombatView.vue     # Combat interface
│   ├── WeaponBar.vue      # Equipment inventory
│   ├── DeathScreen.vue    # Game over screen
│   └── EncumberedView.vue # Over-encumbered state
│
├── App.vue
├── main.ts
└── style.css              # Original visual styling preserved
```

## Current Status

The game is approximately 80% accurate to the original. Known areas needing work:

### Missing/Incomplete Features
- [ ] Room history display
- [ ] More detailed battle log messages (match original text exactly)
- [ ] Stairs up/down between dungeon levels
- [ ] Boss mob spawning on level transitions
- [ ] Corpse display after killing enemies
- [ ] More room description variety (match original roomdesccreator.php)
- [ ] Item comparison when equipping
- [ ] Score calculation display during gameplay

### UI/UX Differences
- [ ] Match original table-based layout more closely
- [ ] Original had different mobile vs desktop layouts
- [ ] Button/link styling differences
- [ ] Font sizing and spacing adjustments

### Mechanics to Verify
- [ ] Experience curve matches original
- [ ] Mob stat distribution algorithm
- [ ] Chest loot tables and probabilities
- [ ] Body part hit distribution percentages
- [ ] Bleeding damage calculations

## Development

```bash
# Install dependencies
npm install

# Run development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

## Deployment

Built for GitHub Pages deployment. The `vite.config.ts` has `base: '/textlike/'` configured.

To deploy:
1. Run `npm run build`
2. Deploy the `dist/` folder to GitHub Pages

## Contributing

When making changes:

1. **Always check the original PHP** - Before implementing or "fixing" something, verify the behavior in the original PHP code
2. **Match exactly** - Don't improve or modernize unless specifically asked
3. **Test against original** - If possible, run the PHP version to compare behavior
4. **Document discrepancies** - If you find the Vue version differs from PHP, note it in this README

## License

Same license as the original Textlike project.
