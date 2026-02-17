<script setup>
import { defineProps } from "vue";
import Chart from 'primevue/chart';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale, Filler } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale, Filler);

defineProps({
    title: { type: String, default: '' },
    chartData: { type: Object, required: true },
    chartOptions: { type: Object, default: () => ({}) },
    height: { type: String, default: 'h-[350px]' }
});
</script>

<template>
    <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800">
        <div class="flex items-center justify-between mb-6">
            <h3 v-if="title" class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ title }}</h3>
            <slot name="action"></slot>
        </div>
        <div :class="height">
            <Chart type="line" :data="chartData" :options="chartOptions" class="h-full" />
        </div>
        <slot name="footer"></slot>
    </div>
</template>