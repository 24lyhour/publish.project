<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight" :style="styles.header">
                Dashboard
            </h2>
        </template>

        <v-container fluid class="py-6" :style="styles.container">
            <!-- Skeleton Loader Section -->
            <div v-if="isLoading">
                <v-row>
                    <v-col v-for="n in 4" :key="n" cols="12" sm="6" md="3">
                        <v-skeleton-loader type="card-avatar" :style="styles.card"></v-skeleton-loader>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" lg="8">
                        <v-skeleton-loader type="image" height="300" :style="styles.card"></v-skeleton-loader>
                    </v-col>
                    <v-col cols="12" lg="4">
                        <v-skeleton-loader type="image" height="300" :style="styles.card"></v-skeleton-loader>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-skeleton-loader type="image" height="300" :style="styles.card"></v-skeleton-loader>
                    </v-col>
                </v-row>
            </div>

            <!-- Dashboard Content Section -->
            <div v-else>
                <transition name="fade" appear>
                    <div>
                        <!-- 4 Stat Cards with Hover Effect -->
                        <v-row>
                            <v-col v-for="stat in statsCards" :key="stat.title" cols="12" sm="6" md="3">
                                <v-card class="pa-2 hover-card" elevation="0" :style="styles.card">
                                    <v-card-text class="d-flex align-center">
                                        <v-avatar :style="styles.icon.avatar" size="50" class="mr-4">
                                            <v-icon :icon="stat.icon" :style="styles.icon.icon" size="30"></v-icon>
                                        </v-avatar>
                                        <div>
                                            <div :style="styles.stat.value">{{ stat.value }}</div>
                                            <div :style="styles.stat.title">{{ stat.title }}</div>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>

                        <!-- Charts -->
                        <v-row>
                            <v-col cols="12" lg="8">
                                <v-card class="hover-card" elevation="0" :style="styles.card">
                                    <v-card-title :style="styles.cardTitle">Sales Analytics</v-card-title>
                                    <v-card-text>
                                        <Bar :data="barChartData" :options="chartOptions" style="height: 350px;" />
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" lg="4">
                                <v-card class="hover-card" elevation="0" :style="styles.card">
                                    <v-card-title :style="styles.cardTitle">Product Categories</v-card-title>
                                    <v-card-text>
                                        <Doughnut :data="doughnutChartData" :options="doughnutChartOptions"
                                            style="height: 350px;" />
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
                                <v-card class="hover-card" elevation="0" :style="styles.card">
                                    <v-card-title :style="styles.cardTitle">Revenue Over Time</v-card-title>
                                    <v-card-text>
                                        <Line :data="areaLineChartData" :options="chartOptions"
                                            style="height: 350px;" />
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </div>
                </transition>
            </div>
        </v-container>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Bar, Line, Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Filler, Chart } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Filler);

const isLoading = ref(true);

/**
 * Palette Color Scheme
 * 
 */
const palette = {
    background: '#F9FAFB', // Slightly off-white for a softer feel
    cardBackground: '#FFFFFF',
    cardBorder: '#F3F4F6', // Even softer border
    textPrimary: '#1F2937', // Dark, slightly desaturated black
    textSecondary: '#6B7280',
    accent: '#3B82F6', // A beautiful, modern blue
    accentLight: 'rgba(59, 130, 246, 0.1)', // For area fill
    iconGradient: 'linear-gradient(135deg, #E5E7EB 0%, #F9FAFB 100%)', // Gradient for icons
};

/**
 * Reactive Styles Object
 * 
 */
const styles = reactive({
    container: { backgroundColor: palette.background },
    header: { color: palette.textPrimary },
    card: {
        borderRadius: '15px',
        border: `1px solid ${palette.cardBorder}`,
        backgroundColor: palette.cardBackground,
        color: palette.textPrimary,
        transition: 'transform 0.3s ease, box-shadow 0.3s ease', // Added for hover effect
    },
    cardTitle: { fontSize: '1.1rem', fontWeight: '600', color: palette.textPrimary },
    icon: {
        avatar: { backgroundImage: palette.iconGradient },
        icon: { color: palette.textPrimary },
    },
    stat: {
        value: { fontSize: '1.75rem', fontWeight: 'bold', color: palette.textPrimary },
        title: { fontSize: '0.9rem', color: palette.textSecondary },
    },
});

/**
 * Stats Cards Data
 * 
 */
const statsCards = ref([
    { title: 'Total Sales', value: '$24,850', icon: 'mdi-chart-line' },
    { title: 'New Orders', value: '356', icon: 'mdi-package-variant-closed' },
    { title: 'New Users', value: '89', icon: 'mdi-account-plus-outline' },
    { title: 'Support Tickets', value: '12', icon: 'mdi-lifebuoy' },
]);

/**
 * Chart Options Object
 * 
 */
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { labels: { color: palette.textSecondary } },
    },
    scales: {
        y: { beginAtZero: true, ticks: { color: palette.textSecondary }, grid: { color: palette.cardBorder } },
        x: { ticks: { color: palette.textSecondary }, grid: { display: false } }, // Hide vertical grid lines for a cleaner look
    },
};

/**
 * Doughnut Chart Options
 * 
 *
 */
const doughnutChartOptions = {
    ...chartOptions,
    scales: { y: { display: false }, x: { display: false } }, // No scales on doughnut
    cutout: '60%', // Makes the donut thinner and more modern
};

/**
 * Bar Chart Data
 * 
 */
const barChartData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [{
        label: 'Sales',
        backgroundColor: palette.accent,
        borderColor: palette.accent,
        borderWidth: 1,
        borderRadius: 5,
        data: [65, 59, 80, 81, 56, 55, 40],
    }],
};

/**
 * Computed Area Line Chart Data
 * 
 */
const areaLineChartData = computed(() => {
    // This trick allows us to use the canvas context to create a gradient
    const chartContext = document.createElement('canvas').getContext('2d');
    if (!chartContext) return {}; // Return empty if context fails

    const gradient = chartContext.createLinearGradient(0, 0, 0, 350);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)'); // Lighter accent at top
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');   // Fades to transparent

    return {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Revenue',
            data: [28, 48, 40, 19, 86, 27, 90],
            borderColor: palette.accent,
            backgroundColor: gradient, // Use the created gradient
            pointBackgroundColor: palette.accent,
            pointBorderColor: '#fff',
            pointHoverRadius: 7,
            tension: 0.4,
            fill: true,
        }],
    };
});

/**
 * Doughnut Chart Data
 * 
 */
const doughnutChartData = {
    labels: ['Electronics', 'Fashion', 'Home Goods', 'Groceries'],
    datasets: [{
        backgroundColor: ['#3B82F6', '#60A5FA', '#93C5FD', '#BFDBFE'], // Monochromatic blue scale
        data: [40, 20, 80, 10],
        hoverOffset: 12, // Makes the hovered segment pop out more
        borderWidth: 0,
    }],
};

/**
 * Life Cycle Hook
 * 
 */
onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 1500); // Reduced delay for a snappier feel
});
</script>

<style scoped>
/* Fade transition for content */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Class for the interactive hover effect on cards */
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.07);
}
</style>