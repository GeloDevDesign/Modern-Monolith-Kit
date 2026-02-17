<script setup lang="ts">
/**
 * Dashboard Home Page
 * 
 * This component displays the main dashboard with statistics, charts, and data tables.
 * It uses PrimeVue components and Chart.js for data visualization.
 * wawie wawie </3
 */

// --- Imports ---

// Vue & Inertia
import { ref, onMounted, watch } from "vue";
import { Head } from "@inertiajs/vue3";

// Layouts & Components
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PageHeader from '@/Components/PageHeader.vue';
import { useThemeStore } from "@/Stores/theme";

// Generic Chart Components
import LineChart from "@/Components/Charts/LineChart.vue";
import DonutChart from "@/Components/Charts/DonutChart.vue";
import BarChart from "@/Components/Charts/BarChart.vue";

// PrimeVue Components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

// --- State: UI & Navigation ---
const breadcrumbItems = ref([
    { icon: 'pi pi-home', route: 'dashboard.index' },
    { label: 'Dashboard' }
]);

const themeStore = useThemeStore();

// --- Chart Data & Options ---
const revenueChartData = ref<any>(null);
const revenueChartOptions = ref<any>(null);
const trafficChartData = ref<any>(null);
const trafficChartOptions = ref<any>(null);
const salesChartData = ref<any>(null);
const salesChartOptions = ref<any>(null);

const getThemeColors = () => {
    const dark = themeStore.isDark;
    return {
        textColor: dark ? '#e4e4e7' : '#4b5563',
        textColorSecondary: dark ? '#a1a1aa' : '#9ca3af',
        surfaceBorder: dark ? '#3f3f46' : '#e5e7eb',
        gridColor: dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
    };
};

const updateCharts = () => {
    const colors = getThemeColors();

    // 1. Revenue Chart (Line)
    revenueChartData.value = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Revenue',
                data: [6500, 5900, 8000, 8100, 5600, 9500, 12000],
                fill: true,
                backgroundColor: (context: any) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)'); // indigo-500 low opacity
                    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
                    return gradient;
                },
                borderColor: '#6366f1', // indigo-500
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#6366f1'
            },
            {
                label: 'Previous Period',
                data: [2800, 4800, 4000, 1900, 8600, 2700, 9000],
                fill: true,
                backgroundColor: (context: any) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(20, 184, 166, 0.3)'); // teal-500 low opacity
                    gradient.addColorStop(1, 'rgba(20, 184, 166, 0.0)');
                    return gradient;
                },
                borderColor: '#14b8a6', // teal-500
                tension: 0.4,
                borderDash: [5, 5],
                pointBackgroundColor: '#14b8a6',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
            }
        ]
    };
    revenueChartOptions.value = {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: { labels: { color: colors.textColor } },
            tooltip: {
                mode: 'index', intersect: false,
                backgroundColor: 'rgba(0, 0, 0, 0.8)', titleColor: '#fff', bodyColor: '#fff', borderColor: 'rgba(255, 255, 255, 0.1)', borderWidth: 1,
            }
        },
        scales: {
            x: { ticks: { color: colors.textColorSecondary }, grid: { color: colors.gridColor, drawBorder: false } },
            y: { ticks: { color: colors.textColorSecondary, callback: (value: number | string) => '$' + value }, grid: { color: colors.gridColor, drawBorder: false } }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false }
    };

    // 2. Traffic Chart (Donut)
    trafficChartData.value = {
        labels: ['Electronics', 'Fashion', 'Home', 'Sports'],
        datasets: [
            {
                data: [300, 50, 100, 80],
                backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ec4899'],
                hoverBackgroundColor: ['#2563eb', '#d97706', '#059669', '#db2777'],
                borderWidth: 0
            }
        ]     
    };
    trafficChartOptions.value = {
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'right',
                labels: { color: colors.textColor, usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 12 } }
            }
        }
    };

    // 3. Sales Chart (Bar)
    salesChartData.value = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Monthly Sales',
                backgroundColor: '#8b5cf6', hoverBackgroundColor: '#7c3aed',
                data: [65, 59, 80, 81, 56, 55, 40, 70, 60, 50, 85, 90],
                borderRadius: 4, barThickness: 20
            },
            {
                label: 'Target',
                backgroundColor: '#cbd5e1', hoverBackgroundColor: '#94a3b8',
                data: [45, 49, 60, 71, 46, 45, 30, 60, 50, 40, 75, 80],
                borderRadius: 4, barThickness: 20
            }
        ]
    };
    salesChartOptions.value = {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: { legend: { labels: { color: colors.textColor } } },
        scales: {
            x: { ticks: { color: colors.textColorSecondary, font: { weight: 500 } }, grid: { display: false, drawBorder: false } },
            y: { ticks: { color: colors.textColorSecondary }, grid: { color: colors.gridColor, drawBorder: false } }
        }
    };
};

onMounted(() => {
    updateCharts();
});

watch(() => themeStore.isDark, () => {
    updateCharts();
});

// --- State: Dashboard Data ---

// 1. Key Metrics / Stats Cards
const stats = ref([
    { title: 'Total Revenue', value: '$45,231.89', change: '+20.1%', icon: 'pi pi-dollar', color: 'bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300' },
    { title: 'Active Users', value: '2,345', change: '+180.1%', icon: 'pi pi-users', color: 'bg-teal-100 text-teal-600 dark:bg-teal-500/20 dark:text-teal-300' },
    { title: 'New Orders', value: '12,345', change: '+19%', icon: 'pi pi-shopping-cart', color: 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-300' },
    { title: 'Pending Support', value: '123', change: '-4%', icon: 'pi pi-comment', color: 'bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300' },
]);

// 2. Products Table Data
const products = ref([
    { id: '1000', code: 'f230fh0g3', name: 'Bamboo Watch', category: 'Accessories', quantity: 24, inventoryStatus: 'INSTOCK', price: '$65' },
    { id: '1001', code: 'nvklal433', name: 'Black Watch', category: 'Accessories', quantity: 61, inventoryStatus: 'INSTOCK', price: '$72' },
    { id: '1002', code: 'zz21cz3c1', name: 'Blue Band', category: 'Fitness', quantity: 2, inventoryStatus: 'LOWSTOCK', price: '$79' },
    { id: '1003', code: '244wgerg2', name: 'Blue T-Shirt', category: 'Clothing', quantity: 25, inventoryStatus: 'INSTOCK', price: '$29' },
    { id: '1004', code: 'h456wer53', name: 'Bracelet', category: 'Accessories', quantity: 73, inventoryStatus: 'INSTOCK', price: '$15' },
    { id: '1005', code: 'av2231fwg', name: 'Brown Purse', category: 'Accessories', quantity: 0, inventoryStatus: 'OUTOFSTOCK', price: '$120' },
    { id: '1006', code: 'bib36pfvm', name: 'Chakra Bracelet', category: 'Accessories', quantity: 5, inventoryStatus: 'LOWSTOCK', price: '$32' },
]);
</script>

<template>
    <!-- Main Layout Wrapper -->
    <AuthenticatedLayout>
        <div class="flex flex-col gap-6">

            <!-- Section: Header -->
            <PageHeader title="Dashboard" description="Overview of your business performance" />

            <!-- Section: Key Metrics (Stats Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="stat in stats" :key="stat.title" class="p-5 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ stat.title }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ stat.value }}</div>
                        <div class="text-xs font-medium mt-2 flex items-center gap-1" :class="stat.change.startsWith('+') ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                            <i :class="stat.change.startsWith('+') ? 'pi pi-arrow-up text-[0.6rem]' : 'pi pi-arrow-down text-[0.6rem]'"></i>
                            <span>{{ stat.change }}</span>
                            <span class="text-gray-400 dark:text-gray-500 font-normal ml-1">vs last month</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-lg" :class="stat.color">
                        <i :class="stat.icon" class="text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Section: Primary Charts (Revenue & Categories) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revenue Line Chart -->
                <LineChart 
                    class="lg:col-span-2" 
                    title="Revenue Overview" 
                    :chartData="revenueChartData" 
                    :chartOptions="revenueChartOptions"
                >
                    <template #action>
                        <button class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline">View Report</button>
                    </template>
                </LineChart>

                <!-- Category Pie Chart -->
                <DonutChart 
                    title="Sales by Category" 
                    :chartData="trafficChartData" 
                    :chartOptions="trafficChartOptions"
                >
                    <template #footer>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800 flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Sales</span>
                            <span class="font-bold text-lg text-gray-800 dark:text-gray-100">$45,231</span>
                        </div>
                    </template>
                </DonutChart>
            </div>

            <!-- Section: Secondary Charts & Activity (Sales vs Target, Timeline) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                 <!-- Monthly Sales Bar Chart -->
                <BarChart 
                    class="lg:col-span-2" 
                    title="Monthly Sales vs Target" 
                    :chartData="salesChartData" 
                    :chartOptions="salesChartOptions"
                >
                    <template #action>
                        <div class="flex gap-2">
                            <span class="flex items-center text-xs text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-violet-500 mr-1"></span> Sales</span>
                            <span class="flex items-center text-xs text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-slate-300 mr-1"></span> Target</span>
                        </div>
                    </template>
                </BarChart>

                <!-- Recent Activity Timeline -->
                <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6">Recent Activity</h3>
                    <div class="relative pl-4 border-l border-gray-200 dark:border-zinc-700 space-y-8">
                        <!-- Timeline Items -->
                        <div class="relative">
                            <div class="absolute -left-[21px] w-3 h-3 bg-blue-500 rounded-full ring-4 ring-white dark:ring-zinc-900"></div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">New user registered</p>
                            <p class="text-xs text-gray-500 mt-0.5">5 minutes ago</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[21px] w-3 h-3 bg-green-500 rounded-full ring-4 ring-white dark:ring-zinc-900"></div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Order #1234 completed</p>
                            <p class="text-xs text-gray-500 mt-0.5">15 minutes ago</p>
                        </div>
                         <div class="relative">
                            <div class="absolute -left-[21px] w-3 h-3 bg-amber-500 rounded-full ring-4 ring-white dark:ring-zinc-900"></div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Payment pending</p>
                            <p class="text-xs text-gray-500 mt-0.5">1 hour ago</p>
                        </div>
                         <div class="relative">
                            <div class="absolute -left-[21px] w-3 h-3 bg-purple-500 rounded-full ring-4 ring-white dark:ring-zinc-900"></div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Server backup finished</p>
                            <p class="text-xs text-gray-500 mt-0.5">2 hours ago</p>
                        </div>
                    </div>
                     <button class="w-full mt-8 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-dashed border-gray-300 dark:border-zinc-700 rounded-lg hover:border-gray-400 dark:hover:border-zinc-600 transition-colors">
                        View All Activity
                    </button>
                </div>
            </div>

            <!-- Section: Data Table (Top Selling Products) -->
            <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Top Selling Products</h3>
                    <div class="flex gap-2">
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="pi pi-filter"></i></button>
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="pi pi-download"></i></button>
                    </div>
                </div>
                <DataTable :value="products" stripedRows tableStyle="min-width: 50rem"
                    :pt="{
                        thead: { class: 'bg-gray-50 dark:bg-zinc-800/50' },
                        headerRow: { class: 'bg-transparent' },
                        headerCell: { class: 'bg-transparent text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider py-3' },
                        bodyRow: { class: 'hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors' },
                        bodyCell: { class: 'py-4 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-zinc-800' }
                    }"
                >
                    <Column field="name" header="Product">
                         <template #body="slotProps">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-gray-100 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-gray-500">
                                    {{ slotProps.data.name.charAt(0) }}
                                </div>
                                <span class="font-medium">{{ slotProps.data.name }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="code" header="Code"></Column>
                    <Column field="category" header="Category"></Column>
                    <Column field="price" header="Price"></Column>
                    <Column field="quantity" header="Stock"></Column>
                    <Column header="Status">
                        <template #body="slotProps">
                            <span :class="'px-2.5 py-1 rounded-full text-xs font-semibold border ' + 
                                (slotProps.data.inventoryStatus === 'INSTOCK' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/30' : 
                                (slotProps.data.inventoryStatus === 'LOWSTOCK' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-900/30' : 
                                'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30'))">
                                {{ slotProps.data.inventoryStatus }}
                            </span>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
