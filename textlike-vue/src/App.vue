<script setup lang="ts">
import { onMounted } from 'vue'
import { useGameStore } from './stores/gameStore'
import MainMenu from './components/MainMenu.vue'
import GameView from './components/GameView.vue'

const game = useGameStore()

onMounted(() => {
  game.loadGame()
})
</script>

<template>
  <div class="game-container">
    <MainMenu v-if="!game.gameStarted || !game.character" />
    <GameView v-else />
  </div>
</template>

<style scoped>
.game-container {
  min-height: 100vh;
}
</style>
