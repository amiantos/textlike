<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'
import type { Direction } from '../engine/types'

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
</script>

<template>
  <div class="content-box room-view" v-if="game.currentRoom">
    <!-- Intro message for first room -->
    <p v-if="game.introMessage" class="room-desc intro">
      {{ game.introMessage }}
    </p>

    <!-- Room description -->
    <p class="room-desc">{{ game.currentRoom.description }}</p>

    <!-- Enemies in room -->
    <p v-if="game.roomMobs.length > 0" class="room-desc">
      You see
      <template v-for="(mob, index) in game.roomMobs" :key="mob.id">
        <a @click="game.attackMob(mob.id)">{{ mob.name }}</a>
        <template v-if="index < game.roomMobs.length - 2">, </template>
        <template v-else-if="index === game.roomMobs.length - 2"> and </template>
      </template>
      lurking here.
    </p>

    <!-- Chests in room -->
    <p v-for="chest in game.roomChests" :key="chest.id" class="room-desc">
      There is a <a @click="game.openChest(chest.id)">{{ chest.name }}</a> here.
    </p>

    <!-- Items on floor -->
    <p v-if="game.roomItems.weapons.length > 0" class="room-desc">
      On the floor you see:
      <template v-for="(weapon, index) in game.roomItems.weapons" :key="weapon.id">
        <a @click="game.pickUpWeapon(weapon.id)">{{ weapon.name }}</a>
        <template v-if="index < game.roomItems.weapons.length - 1">, </template>
      </template>
    </p>

    <p v-if="game.roomItems.armors.length > 0" class="room-desc">
      Armor on the floor:
      <template v-for="(armor, index) in game.roomItems.armors" :key="armor.id">
        <a @click="game.pickUpArmor(armor.id)">{{ armor.name }}</a>
        <template v-if="index < game.roomItems.armors.length - 1">, </template>
      </template>
    </p>

    <p v-if="game.roomItems.tomes.length > 0" class="room-desc">
      Tomes on the floor:
      <template v-for="(tome, index) in game.roomItems.tomes" :key="tome.id">
        <a @click="game.pickUpTome(tome.id)">{{ tome.name }}</a>
        <template v-if="index < game.roomItems.tomes.length - 1">, </template>
      </template>
    </p>

    <p v-if="game.roomItems.consumables.length > 0" class="room-desc">
      Items on the floor:
      <template v-for="(item, index) in game.roomItems.consumables" :key="item.id">
        <a @click="game.pickUpConsumable(item.id)">{{ item.kind }}</a>
        <template v-if="index < game.roomItems.consumables.length - 1">, </template>
      </template>
    </p>

    <!-- Room exits -->
    <p v-if="game.roomMobs.length > 0" class="room-desc locked">
      The door you came in locked behind you. Maybe killing the enemies here will
      unlock it and reveal others?
    </p>
    <p v-else class="room-desc">
      Exits:
      <template v-for="(_, dir) in game.currentRoom.exits" :key="dir">
        <template v-if="game.currentRoom.exits[dir as Direction]">
          <a @click="move(dir as Direction)">{{ getDirectionName(dir as Direction) }}</a>
          {{ ' ' }}
        </template>
      </template>
      <span v-if="!Object.values(game.currentRoom.exits).some(e => e)">
        None visible yet. Explore to reveal more.
      </span>
    </p>
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
</style>
