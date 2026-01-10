<script setup lang="ts">
import { computed } from 'vue'
import { useGameStore } from '../stores/gameStore'
import type { Direction, Mob } from '../engine/types'

const game = useGameStore()

function move(direction: Direction) {
  game.moveToRoom(direction)
}

function getDirectionName(dir: Direction): string {
  const names: Record<Direction, string> = {
    north: 'North',
    south: 'South',
    east: 'East',
    west: 'West',
    up: 'Up',
    down: 'Down',
  }
  return names[dir]
}

// Check if the room in a given direction has been explored (PHP 0.8.1 strikethrough behavior)
function isExitExplored(dir: Direction): boolean {
  if (!game.currentRoom) return false
  const targetRoomId = game.currentRoom.exits[dir]
  if (!targetRoomId) return false
  const targetRoom = game.rooms.get(targetRoomId)
  // A room is considered "explored" if its exits have been generated
  return targetRoom?.roomsGenerated ?? false
}

// ============================================================================
// PHP-MATCHING HELPER FUNCTIONS
// ============================================================================

/**
 * Returns "a" or "an" based on the first character of the name
 * PHP logic: uses "an" if starts with vowel (a,e,i,o,u) or "ho"
 */
function getArticle(name: string): string {
  const lower = name.toLowerCase()
  const firstChar = lower.charAt(0)
  if (['a', 'e', 'i', 'o', 'u'].includes(firstChar) || lower.startsWith('ho')) {
    return 'an'
  }
  return 'a'
}

/**
 * Get level-based adjective for mob display
 * PHP logic: boss = "absolutely terrifying", level > player+5 = "hulking",
 * level > player+2 = "angry", level < player-1 = "timid", else empty
 */
function getMobAdjective(mob: Mob): string {
  if (!game.character) return ''
  const charLevel = game.character.level

  if (mob.isBoss) return 'absolutely terrifying '
  if (mob.level > charLevel + 5) return 'hulking '
  if (mob.level > charLevel + 2) return 'angry '
  if (mob.level < charLevel - 1) return 'timid '
  return ''
}

/**
 * Get the full display name for a mob (adjective + disposition + kind for normal, adjective + kind for boss)
 */
function getMobDisplayName(mob: Mob): string {
  const adj = getMobAdjective(mob)
  if (mob.isBoss) {
    return adj + mob.kind
  }
  return adj + mob.disposition + ' ' + mob.kind
}

/**
 * Get corpse display name (disposition + kind for normal, "boss" + kind for boss)
 */
function getCorpseDisplayName(mob: Mob): string {
  if (mob.isBoss) {
    return 'boss ' + mob.kind
  }
  return mob.disposition + ' ' + mob.kind
}

// ============================================================================
// COMPUTED PROPERTIES FOR DISPLAY
// ============================================================================

// Horizontal exits only (not stairs)
const horizontalExits = computed(() => {
  if (!game.currentRoom) return []
  const dirs: Direction[] = ['north', 'south', 'east', 'west']
  return dirs.filter(dir => game.currentRoom!.exits[dir] !== null)
})

// Stair exits
const hasStairsDown = computed(() => game.currentRoom?.exits.down !== null)
const hasStairsUp = computed(() => game.currentRoom?.exits.up !== null)
const hasStairs = computed(() => hasStairsDown.value || hasStairsUp.value)

// Bandages and apples counts
const bandagesInRoom = computed(() => {
  return game.roomItems.consumables.filter(c => c.kind === 'Bandages')
})
const applesInRoom = computed(() => {
  return game.roomItems.consumables.filter(c => c.kind === 'Apples')
})

// Helper computed for first items (avoids TypeScript errors with array indexing)
const firstMob = computed(() => game.roomMobs[0])
const secondMob = computed(() => game.roomMobs[1])
const firstCorpse = computed(() => game.roomCorpses[0])
const secondCorpse = computed(() => game.roomCorpses[1])
const firstWeapon = computed(() => game.roomItems.weapons[0])
const secondWeapon = computed(() => game.roomItems.weapons[1])
const firstArmor = computed(() => game.roomItems.armors[0])
const secondArmor = computed(() => game.roomItems.armors[1])
const firstTome = computed(() => game.roomItems.tomes[0])
const secondTome = computed(() => game.roomItems.tomes[1])
const firstBandage = computed(() => bandagesInRoom.value[0])
const firstApple = computed(() => applesInRoom.value[0])
</script>

<template>
  <div class="content-box room-view" v-if="game.currentRoom">
    <!-- Intro message for first room -->
    <p v-if="game.introMessage" class="room-desc intro">
      {{ game.introMessage }}
    </p>

    <!-- Room description -->
    <p class="room-desc">{{ game.currentRoom.description }}</p>

    <!-- ================================================================== -->
    <!-- ENEMIES IN ROOM (PHP load_mobs - living mobs) -->
    <!-- ================================================================== -->

    <!-- 1 mob: "You see a/an [adj][name] lurking amongst the shadows." -->
    <p v-if="game.roomMobs.length === 1 && firstMob" class="room-desc">
      You see {{ getArticle(getMobDisplayName(firstMob)) }}
      <a @click="game.attackMob(firstMob.id)">{{ getMobDisplayName(firstMob) }}</a>
      lurking amongst the shadows.
    </p>

    <!-- 2 mobs: "A/An [name1] and a/an [name2] lurk amongst the shadows." -->
    <p v-else-if="game.roomMobs.length === 2 && firstMob && secondMob" class="room-desc">
      {{ getArticle(getMobDisplayName(firstMob)).charAt(0).toUpperCase() + getArticle(getMobDisplayName(firstMob)).slice(1) }}
      <a @click="game.attackMob(firstMob.id)">{{ getMobDisplayName(firstMob) }}</a>
      and {{ getArticle(getMobDisplayName(secondMob)) }}
      <a @click="game.attackMob(secondMob.id)">{{ getMobDisplayName(secondMob) }}</a>
      lurk amongst the shadows.
    </p>

    <!-- 3+ mobs: "You see that there are several enemies here—[list]—lurking amongst the shadows." -->
    <p v-else-if="game.roomMobs.length >= 3" class="room-desc">
      You see that there are several enemies here&mdash;<template
        v-for="(mob, index) in game.roomMobs" :key="mob.id"
      ><template v-if="index === 0"
        ><a @click="game.attackMob(mob.id)">{{ getMobDisplayName(mob) }}</a></template
      ><template v-else-if="index === game.roomMobs.length - 1"
        >, and {{ getArticle(getMobDisplayName(mob)) }} <a @click="game.attackMob(mob.id)">{{ getMobDisplayName(mob) }}</a></template
      ><template v-else
        >, {{ getArticle(getMobDisplayName(mob)) }} <a @click="game.attackMob(mob.id)">{{ getMobDisplayName(mob) }}</a></template
      ></template>&mdash;lurking amongst the shadows.
    </p>

    <!-- ================================================================== -->
    <!-- CORPSES IN ROOM (PHP load_mobs - dead mobs) -->
    <!-- ================================================================== -->

    <!-- 1 corpse: "The bloodied corpse of a/an [name] lies on the floor." -->
    <p v-if="game.roomCorpses.length === 1 && firstCorpse" class="room-desc">
      The bloodied corpse of {{ getArticle(getCorpseDisplayName(firstCorpse)) }} {{ getCorpseDisplayName(firstCorpse) }} lies on the floor.
    </p>

    <!-- 2 corpses: "The rotting corpses of a/an [name1] and a/an [name2] are beginning to collect flies." -->
    <p v-else-if="game.roomCorpses.length === 2 && firstCorpse && secondCorpse" class="room-desc">
      The rotting corpses of {{ getArticle(getCorpseDisplayName(firstCorpse)) }} {{ getCorpseDisplayName(firstCorpse) }}
      and {{ getArticle(getCorpseDisplayName(secondCorpse)) }} {{ getCorpseDisplayName(secondCorpse) }}
      are beginning to collect flies.
    </p>

    <!-- 3+ corpses: "A pile of corpses—consisting of the bodies of [list]—are really stinking up the room." -->
    <p v-else-if="game.roomCorpses.length >= 3" class="room-desc">
      A pile of corpses&mdash;consisting of the bodies of <template
        v-for="(corpse, index) in game.roomCorpses" :key="corpse.id"
      ><template v-if="index === 0"
        >{{ getArticle(getCorpseDisplayName(corpse)) }} {{ getCorpseDisplayName(corpse) }}</template
      ><template v-else-if="index === game.roomCorpses.length - 1"
        >, and {{ getArticle(getCorpseDisplayName(corpse)) }} {{ getCorpseDisplayName(corpse) }}</template
      ><template v-else
        >, {{ getArticle(getCorpseDisplayName(corpse)) }} {{ getCorpseDisplayName(corpse) }}</template
      ></template>&mdash;are really stinking up the room.
    </p>

    <!-- ================================================================== -->
    <!-- CHESTS (PHP load_chests) -->
    <!-- ================================================================== -->
    <p v-for="chest in game.roomChests" :key="chest.id" class="room-desc">
      There is a {{ chest.name.toLowerCase() }} here. <a @click="game.openChest(chest.id)">Open it</a>?
    </p>

    <!-- ================================================================== -->
    <!-- WEAPONS ON FLOOR (PHP load_weapons) -->
    <!-- ================================================================== -->

    <!-- 1 weapon: "There's a [weapon] lying on the floor." -->
    <p v-if="game.roomItems.weapons.length === 1 && firstWeapon" class="room-desc">
      There's a <span class="weapon"><a @click="game.pickUpWeapon(firstWeapon.id)">{{ firstWeapon.name }}</a></span> lying on the floor.
    </p>

    <!-- 2 weapons: "There's a [weapon1], and a [weapon2] on the floor." -->
    <p v-else-if="game.roomItems.weapons.length === 2 && firstWeapon && secondWeapon" class="room-desc">
      There's a <span class="weapon"><a @click="game.pickUpWeapon(firstWeapon.id)">{{ firstWeapon.name }}</a></span>, and a <span class="weapon"><a @click="game.pickUpWeapon(secondWeapon.id)">{{ secondWeapon.name }}</a></span> on the floor.
    </p>

    <!-- 3+ weapons: "In a pile on the floor, you see a [list]." -->
    <p v-else-if="game.roomItems.weapons.length >= 3" class="room-desc">
      In a pile on the floor, you see a <template
        v-for="(weapon, index) in game.roomItems.weapons" :key="weapon.id"
      ><template v-if="index === game.roomItems.weapons.length - 1"
        >and a <span class="weapon"><a @click="game.pickUpWeapon(weapon.id)">{{ weapon.name }}</a></span></template
      ><template v-else
        ><span class="weapon"><a @click="game.pickUpWeapon(weapon.id)">{{ weapon.name }}</a></span>, </template
      ></template>.
    </p>

    <!-- ================================================================== -->
    <!-- TOMES ON SHELF (PHP load_tomes) -->
    <!-- ================================================================== -->

    <!-- 1-2 tomes: "On a shelf, there's a [tome]." -->
    <p v-if="game.roomItems.tomes.length === 1 && firstTome" class="room-desc">
      On a shelf, there's a <span class="tome"><a @click="game.pickUpTome(firstTome.id)">{{ firstTome.name }}</a></span>.
    </p>

    <p v-else-if="game.roomItems.tomes.length === 2 && firstTome && secondTome" class="room-desc">
      On a shelf, there's a <span class="tome"><a @click="game.pickUpTome(firstTome.id)">{{ firstTome.name }}</a></span>, and a <span class="tome"><a @click="game.pickUpTome(secondTome.id)">{{ secondTome.name }}</a></span>.
    </p>

    <!-- 3+ tomes: "On a shelf, you see a [list]." -->
    <p v-else-if="game.roomItems.tomes.length >= 3" class="room-desc">
      On a shelf, you see a <template
        v-for="(tome, index) in game.roomItems.tomes" :key="tome.id"
      ><template v-if="index === game.roomItems.tomes.length - 1"
        >and a <span class="tome"><a @click="game.pickUpTome(tome.id)">{{ tome.name }}</a></span></template
      ><template v-else
        ><span class="tome"><a @click="game.pickUpTome(tome.id)">{{ tome.name }}</a></span>, </template
      ></template>.
    </p>

    <!-- ================================================================== -->
    <!-- ARMOR ON FLOOR (PHP load_armor) -->
    <!-- ================================================================== -->

    <!-- 1 armor: "There's a [armor] armor lying on the floor." -->
    <p v-if="game.roomItems.armors.length === 1 && firstArmor" class="room-desc">
      There's a <span class="armor"><a @click="game.pickUpArmor(firstArmor.id)">{{ firstArmor.name }}</a></span> armor lying on the floor.
    </p>

    <!-- 2 armors: "There are [armor1], and [armor2] armors on the floor." -->
    <p v-else-if="game.roomItems.armors.length === 2 && firstArmor && secondArmor" class="room-desc">
      There are <span class="armor"><a @click="game.pickUpArmor(firstArmor.id)">{{ firstArmor.name }}</a></span>, and <span class="armor"><a @click="game.pickUpArmor(secondArmor.id)">{{ secondArmor.name }}</a></span> armors on the floor.
    </p>

    <!-- 3+ armors: "Scattered around the room are [list] armors." -->
    <p v-else-if="game.roomItems.armors.length >= 3" class="room-desc">
      Scattered around the room are <template
        v-for="(armor, index) in game.roomItems.armors" :key="armor.id"
      ><template v-if="index === game.roomItems.armors.length - 1"
        >and <span class="armor"><a @click="game.pickUpArmor(armor.id)">{{ armor.name }}</a></span></template
      ><template v-else
        ><span class="armor"><a @click="game.pickUpArmor(armor.id)">{{ armor.name }}</a></span>, </template
      ></template> armors.
    </p>

    <!-- ================================================================== -->
    <!-- CONSUMABLES (PHP load_items - bandages and apples separately) -->
    <!-- ================================================================== -->

    <!-- Bandages: "There is a single roll of / pair of / pile of bandages here." -->
    <p v-if="bandagesInRoom.length > 0 && firstBandage" class="room-desc">
      <span class="healing">There is
        <template v-if="bandagesInRoom.length === 1">a single roll of </template>
        <template v-else-if="bandagesInRoom.length === 2">a pair of </template>
        <template v-else>a pile of </template>
        <a @click="game.pickUpConsumable(firstBandage.id)">bandages</a> here.</span>
    </p>

    <!-- Apples: "There is a single apple / pair of apples / pile of apples here." -->
    <p v-if="applesInRoom.length > 0 && firstApple" class="room-desc">
      <span class="healing">There is
        <template v-if="applesInRoom.length === 1">a single <a @click="game.pickUpConsumable(firstApple.id)">apple</a></template>
        <template v-else-if="applesInRoom.length === 2">a pair of <a @click="game.pickUpConsumable(firstApple.id)">apples</a></template>
        <template v-else>a pile of <a @click="game.pickUpConsumable(firstApple.id)">apples</a></template>
        here.</span>
    </p>

    <!-- ================================================================== -->
    <!-- ROOM EXITS (PHP find_exits) -->
    <!-- ================================================================== -->

    <!-- When mobs present, don't show exits (PHP just doesn't call find_exits when mobs) -->
    <!-- But if there are stairs and boss not killed, show locked message -->
    <template v-if="game.roomMobs.length > 0">
      <!-- PHP only shows lock message for stairs, not regular doors -->
      <!-- Regular exits are simply not displayed when mobs present -->
    </template>

    <template v-else>
      <!-- Horizontal exits: "There is a doorway / doorways leading to the [directions]." -->
      <p v-if="horizontalExits.length > 0" class="room-desc">
        <template v-if="horizontalExits.length === 1">There is a doorway leading to the </template>
        <template v-else>There are doorways leading to the </template>
        <template v-for="(dir, index) in horizontalExits" :key="dir">
          <template v-if="index === horizontalExits.length - 1 && horizontalExits.length > 1"> and </template>
          <a
            @click="move(dir)"
            :class="{ explored: isExitExplored(dir) }"
          >{{ getDirectionName(dir) }}</a>
          <template v-if="index < horizontalExits.length - 2">, </template>
        </template>
        <template v-if="!hasStairs">.</template>
        <template v-else>. In the center of the room, a staircase leading
          <template v-if="hasStairsDown">
            <a
              @click="move('down')"
              :class="{ explored: isExitExplored('down') }"
            >downward</a>.
          </template>
          <template v-if="hasStairsUp">
            <a
              @click="move('up')"
              :class="{ explored: isExitExplored('up') }"
            >upward</a>.
          </template>
        </template>
      </p>

      <!-- No horizontal exits but has stairs -->
      <p v-else-if="hasStairs" class="room-desc">
        In the center of the room, a staircase leading
        <template v-if="hasStairsDown">
          <a
            @click="move('down')"
            :class="{ explored: isExitExplored('down') }"
          >downward</a>.
        </template>
        <template v-if="hasStairsUp">
          <a
            @click="move('up')"
            :class="{ explored: isExitExplored('up') }"
          >upward</a>.
        </template>
      </p>

      <!-- No exits at all -->
      <p v-else class="room-desc">
        There are no visible exits.
      </p>
    </template>
  </div>
</template>

<style scoped>
.room-view a {
  cursor: pointer;
}

.intro {
  font-style: italic;
}

.locked {
  color: #666;
  font-style: italic;
}

/* Strikethrough for exits leading to explored rooms (PHP 0.8.1 behavior) */
.explored {
  text-decoration: line-through;
}

.weapon a {
  color: inherit;
}

.armor a {
  color: inherit;
}

.tome a {
  color: inherit;
}

.healing a {
  color: inherit;
}
</style>
