<script setup lang="ts">
import { computed } from 'vue'
import { useGameStore } from '../stores/gameStore'
import { getExperienceProgress, getTotalWounds } from '../engine/character'

const game = useGameStore()

const expProgress = computed(() => {
  if (!game.character) return 0
  return getExperienceProgress(game.character)
})

const bleeding = computed(() => {
  if (!game.character) return 0
  return getTotalWounds(game.character)
})

const hasAvailablePoints = computed(() => {
  return game.character && game.character.availablePoints > 0
})

function allocateStat(stat: 'strength' | 'stamina' | 'dexterity' | 'intelligence' | 'luck') {
  game.allocateStatPoint(stat)
}
</script>

<template>
  <div class="content-box stat-bar" v-if="game.character">
    <table>
      <tr>
        <td rowspan="3" class="logo-cell">
          <div class="logo-container">
            <img src="/logo.png" class="logo" alt="Textlike" />
          </div>
        </td>
        <td>
          <div class="stat-row">
            <div class="stat-item">
              <span :class="{ 'name-small': game.character.name.length > 10 }">
                {{ game.character.name }}
              </span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Weight:</span>
              {{ game.currentWeight }}\{{ game.character.totalCarry }}
            </div>
            <div class="stat-item">
              <span class="stat-label">Health:</span>
              {{ game.character.currentHealth }}
              <span v-if="bleeding > 0" class="bleeding">({{ bleeding }})</span>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="stat-row">
            <div class="stat-item">
              <span class="stat-label">Score:</span> {{ game.score }}
            </div>
            <div class="stat-item">
              <span class="stat-label">Level:</span>
              <span :class="{ 'has-points': hasAvailablePoints }">
                {{ game.character.level }}
              </span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Next:</span> {{ expProgress }}%
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="stat-row attributes">
            <div class="stat-item">
              <span class="stat-label">Str:</span>
              {{ game.character.strength }}
              <a v-if="hasAvailablePoints" @click="allocateStat('strength')" class="plus-btn">+</a>
            </div>
            <div class="stat-item">
              <span class="stat-label">Sta:</span>
              {{ game.character.stamina }}
              <a v-if="hasAvailablePoints" @click="allocateStat('stamina')" class="plus-btn">+</a>
            </div>
            <div class="stat-item">
              <span class="stat-label">Dex:</span>
              {{ game.character.dexterity }}
              <a v-if="hasAvailablePoints" @click="allocateStat('dexterity')" class="plus-btn">+</a>
            </div>
            <div class="stat-item">
              <span class="stat-label">Int:</span>
              {{ game.character.intelligence }}
              <a v-if="hasAvailablePoints" @click="allocateStat('intelligence')" class="plus-btn">+</a>
            </div>
            <div class="stat-item">
              <span class="stat-label">Lck:</span>
              {{ game.character.luck }}
              <a v-if="hasAvailablePoints" @click="allocateStat('luck')" class="plus-btn">+</a>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<style scoped>
.logo-cell {
  width: 60px;
  border-right: 1px solid #808080;
  vertical-align: middle;
}

.logo-container {
  text-align: center;
  padding: 5px;
}

.name-small {
  font-size: 0.85em;
}

.has-points {
  color: red;
  font-weight: bold;
}

.plus-btn {
  color: green;
  font-weight: bold;
  cursor: pointer;
  margin-left: 2px;
}

.plus-btn:hover {
  text-decoration: none;
}

.attributes .stat-item {
  font-size: 0.9em;
}
</style>
