<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'

const game = useGameStore()
</script>

<template>
  <div class="content-box encumbered-view">
    <h2>You're Encumbered</h2>
    <p class="room-desc">
      You've become so weighed down with stuff you're no longer able to move. It's
      probably best if you lightened your load before you progress.
    </p>
    <p class="room-desc">
      Current weight: {{ game.currentWeight }} / {{ game.character?.totalCarry }}
    </p>
  </div>
</template>

<style scoped>
.encumbered-view h2 {
  color: #996600;
}
</style>
