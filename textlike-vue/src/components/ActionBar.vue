<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'

const game = useGameStore()

function attack() {
  game.performCombatAction('attack')
}

function defend() {
  game.performCombatAction('defend')
}

function useTome() {
  game.performCombatAction('tome')
}
</script>

<template>
  <div class="content-box action-bar" v-if="game.character">
    <div class="action-bar-inner">
      <!-- Left side: Items -->
      <div class="items">
        <span v-if="game.bandagesCount > 0">
          <a @click="game.useBandage()">Bandages</a> ({{ game.bandagesCount }})
        </span>
        <span v-if="game.bandagesCount > 0 && game.applesCount > 0"> &middot; </span>
        <span v-if="game.applesCount > 0">
          <a @click="game.useApple()">Apples</a> ({{ game.applesCount }})
        </span>
      </div>

      <!-- Right side: Combat actions or Floor -->
      <div class="actions">
        <template v-if="game.isInCombat">
          <a @click="defend">Defend</a>
          <span> &middot; </span>
          <a v-if="game.equippedTome" @click="useTome" class="combat-action">Tome</a>
          <span v-if="game.equippedTome"> &middot; </span>
          <a v-if="game.equippedWeapon" @click="attack" class="combat-action">Attack</a>
          <a v-else @click="attack">Punch</a>
          <span v-if="!game.equippedArmor"> &middot; No Armor</span>
        </template>
        <template v-else>
          Floor {{ game.currentFloor }}
        </template>
      </div>
    </div>
  </div>
</template>

<style scoped>
.action-bar-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 5px;
}

.items a, .actions a {
  cursor: pointer;
}

.combat-action {
  color: red;
}
</style>
