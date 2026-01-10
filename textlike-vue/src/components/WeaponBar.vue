<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'

const game = useGameStore()
</script>

<template>
  <div class="content-box weapon-bar" v-if="game.character">
    <table class="inventory-table">
      <!-- Equipped Weapon -->
      <tr v-if="game.equippedWeapon">
        <td class="item-name equipped">
          {{ game.equippedWeapon.name }}
          <span class="item-stats">
            (<span class="stat-damage" :title="'Damage: ' + game.equippedWeapon.damage">{{ game.equippedWeapon.damage }}</span>/<span class="stat-durability" :title="'Durability: ' + game.equippedWeapon.currentDurability">{{ game.equippedWeapon.currentDurability }}</span>/<span class="stat-weight" :title="'Weight: ' + game.equippedWeapon.weight">{{ game.equippedWeapon.weight }}</span>)
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.dropWeapon(game.equippedWeapon!.id)">Drop</a>
        </td>
      </tr>

      <!-- Other Weapons in Inventory -->
      <tr v-for="weapon in game.inventory.weapons.filter(w => w.id !== game.equippedWeapon?.id)" :key="weapon.id">
        <td class="item-name">
          {{ weapon.name }}
          <span class="item-stats">
            (<span class="stat-damage" :title="'Damage: ' + weapon.damage">{{ weapon.damage }}</span>/<span class="stat-durability" :title="'Durability: ' + weapon.currentDurability">{{ weapon.currentDurability }}</span>/<span class="stat-weight" :title="'Weight: ' + weapon.weight">{{ weapon.weight }}</span>)
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.equipWeapon(weapon.id)">Equip</a>
          &middot;
          <a @click="game.dropWeapon(weapon.id)">Drop</a>
        </td>
      </tr>

      <!-- Equipped Armor -->
      <tr v-if="game.equippedArmor">
        <td class="item-name equipped">
          {{ game.equippedArmor.name }}
          <span class="item-stats">
            (<span class="stat-protection" :title="'Protection: ' + game.equippedArmor.protection">{{ game.equippedArmor.protection }}</span>/<span class="stat-durability" :title="'Durability: ' + game.equippedArmor.currentDurability">{{ game.equippedArmor.currentDurability }}</span>/<span class="stat-weight" :title="'Weight: ' + game.equippedArmor.weight">{{ game.equippedArmor.weight }}</span>)
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.dropArmor(game.equippedArmor!.id)">Drop</a>
        </td>
      </tr>

      <!-- Other Armor in Inventory -->
      <tr v-for="armor in game.inventory.armors.filter(a => a.id !== game.equippedArmor?.id)" :key="armor.id">
        <td class="item-name">
          {{ armor.name }}
          <span class="item-stats">
            (<span class="stat-protection" :title="'Protection: ' + armor.protection">{{ armor.protection }}</span>/<span class="stat-durability" :title="'Durability: ' + armor.currentDurability">{{ armor.currentDurability }}</span>/<span class="stat-weight" :title="'Weight: ' + armor.weight">{{ armor.weight }}</span>)
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.equipArmor(armor.id)">Equip</a>
          &middot;
          <a @click="game.dropArmor(armor.id)">Drop</a>
        </td>
      </tr>

      <!-- Equipped Tome -->
      <tr v-if="game.equippedTome">
        <td class="item-name equipped">
          {{ game.equippedTome.name }}
          <span class="item-stats">
            (<span class="stat-tome-damage" :title="'Damage: ' + game.equippedTome.damage">{{ game.equippedTome.damage }}</span>/<span class="stat-durability" :title="'Durability: ' + game.equippedTome.currentDurability">{{ game.equippedTome.currentDurability }}</span>/<span class="stat-weight" :title="'Weight: ' + game.equippedTome.weight">{{ game.equippedTome.weight }}</span>)
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.dropTome(game.equippedTome!.id)">Drop</a>
        </td>
      </tr>

      <!-- Other Tomes in Inventory -->
      <tr v-for="tome in game.inventory.tomes.filter(t => t.id !== game.equippedTome?.id)" :key="tome.id">
        <td class="item-name">
          {{ tome.name }}
          <span class="item-stats">
            (<span class="stat-tome-damage" :title="'Damage: ' + tome.damage">{{ tome.damage }}</span>/<span class="stat-durability" :title="'Durability: ' + tome.currentDurability">{{ tome.currentDurability }}</span>/<span class="stat-weight" :title="'Weight: ' + tome.weight">{{ tome.weight }}</span>)
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.equipTome(tome.id)">Equip</a>
          &middot;
          <a @click="game.dropTome(tome.id)">Drop</a>
        </td>
      </tr>

      <!-- Empty state -->
      <tr v-if="game.inventory.weapons.length === 0 && game.inventory.armors.length === 0 && game.inventory.tomes.length === 0">
        <td colspan="2" class="text-center">
          <em>No equipment</em>
        </td>
      </tr>
    </table>
  </div>
</template>

<style scoped>
.weapon-bar {
  margin-top: 10px;
}

.inventory-table {
  width: 100%;
}

.inventory-table td {
  padding: 4px;
}

.item-name {
  width: 70%;
}

.item-name.equipped {
  font-weight: bold;
}

.item-actions {
  text-align: right;
  white-space: nowrap;
}

.item-actions a {
  cursor: pointer;
}

/* Item stats display - colored numbers matching PHP 0.8.1 */
.item-stats {
  font-size: 0.85em;
}

/* Weapon damage - red */
.stat-damage {
  color: red;
}

/* Armor protection - green */
.stat-protection {
  color: green;
}

/* Tome damage - purple */
.stat-tome-damage {
  color: purple;
}

/* Durability - blue */
.stat-durability {
  color: blue;
}

/* Weight - orange */
.stat-weight {
  color: orange;
}
</style>
