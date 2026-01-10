<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'
import StatBar from './StatBar.vue'
import ActionBar from './ActionBar.vue'
import RoomView from './RoomView.vue'
import CombatView from './CombatView.vue'
import DeathScreen from './DeathScreen.vue'
import EncumberedView from './EncumberedView.vue'
import WeaponBar from './WeaponBar.vue'

const game = useGameStore()
</script>

<template>
  <div class="game-view" v-if="game.character">
    <!-- Stat Bar - Always visible -->
    <StatBar />

    <!-- Action Bar - When not dead -->
    <ActionBar v-if="!game.character.isDead" />

    <!-- Main Content Area -->
    <DeathScreen v-if="game.character.isDead" />
    <EncumberedView v-else-if="game.isPlayerEncumbered" />
    <CombatView v-else-if="game.isInCombat" />
    <RoomView v-else />

    <!-- Weapon Bar - When not dead -->
    <WeaponBar v-if="!game.character.isDead" />
  </div>
</template>

<style scoped>
.game-view {
  padding-bottom: 20px;
}
</style>
