<template>
    <AuthenticatedLayout title="Product Details">
        <v-container fluid class="py-6">
            <!-- Header Section -->
            <v-card class="mb-6 hover-card elevation-0" rounded="lg">
            <v-card-title class="d-flex align-center justify-space-between">
                <div class="d-flex align-center">
                    <v-btn icon variant="text" @click="handleCallBack" class="mr-3">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                    <div>
                        <h2 class="text-h5 font-weight-bold">Product Details <VIcon>mdi-package-variant-closed</VIcon>
                        </h2>
                        <p class="text-subtitle-2 text-medium-emphasis mb-0">
                            ID: {{ product.id }} | SKU: {{ product.sku || 'N/A' }}
                        </p>
                    </div>
                </div>
            </v-card-title>
        </v-card>

        <v-row>
            <!-- Left Column - Product Image & Basic Info -->
            <v-col cols="12" md="5">
                <!-- Product Image -->
                <v-card class="mb-4 hover-card hover-lift elevation-0" rounded="lg">
                    <v-card-title class="pb-2">
                        <v-icon start>mdi-image</v-icon>
                        Product Image
                    </v-card-title>
                    <v-card-text>
                        <div class="image-container">
                            <v-img :src="product.imageUrl" :alt="product.name" aspect-ratio="1" class="product-image"
                                cover>
                                <template v-slot:placeholder>
                                    <div class="d-flex align-center justify-center fill-height">
                                        <v-progress-circular indeterminate></v-progress-circular>
                                    </div>
                                </template>
                            </v-img>

                            <!-- Status Badges Overlay -->
                            <div class="status-badges">
                                <v-chip v-if="product.isNew" color="success" size="small" variant="elevated">
                                    <v-icon start>mdi-new-box</v-icon>
                                    NEW
                                </v-chip>
                                <v-chip v-if="product.onSale" color="error" size="small" variant="elevated">
                                    <v-icon start>mdi-sale</v-icon>
                                    SALE
                                </v-chip>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>


            </v-col>

            <!-- Right Column - Detailed Information -->
            <v-col cols="12" md="7">
                <!-- Product Information -->
                <v-card class="mb-4 hover-card hover-glow elevation-0" rounded="lg">
                    <v-card-title>
                        <v-icon start>mdi-information</v-icon>
                        Product Information
                    </v-card-title>
                    <v-card-text>
                        <!-- Product Name & Category -->
                        <div class="mb-4">
                            <v-chip v-if="product.category" color="primary" variant="outlined" size="small"
                                class="mb-2">
                                {{ product.category }}
                            </v-chip>
                            <h3 class="text-h5 font-weight-bold mb-2">{{ product.name }}</h3>
                            <p v-if="product.brand" class="text-subtitle-1 text-medium-emphasis">
                                Brand: {{ product.brand }}
                            </p>
                        </div>

                        <!-- Pricing Section -->
                        <div class="pricing-section mb-4">
                            <v-row dense align="center">
                                <v-col cols="auto">
                                    <div class="text-h6 font-weight-bold text-success">
                                        {{ formattedPrice }}
                                    </div>
                                </v-col>
                                <v-col cols="auto" v-if="hasDiscount">
                                    <div class="text-body-2 text-decoration-line-through text-medium-emphasis">
                                        {{ formattedOriginalPrice }}
                                    </div>
                                </v-col>
                                <v-col cols="auto" v-if="hasDiscount">
                                    <v-chip color="success" size="small" variant="tonal">
                                        {{ discountPercentage }}% OFF
                                    </v-chip>
                                </v-col>
                            </v-row>
                        </div>

                        <!-- Rating -->
                        <div v-if="product.rating" class="rating-section mb-4">
                            <div class="d-flex align-center gap-3">
                                <v-rating :model-value="product.rating" color="amber" density="compact"
                                    readonly></v-rating>
                                <span class="text-body-2">
                                    {{ product.rating }}/5 ({{ product.reviewCount || 0 }} reviews)
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="description-section">
                            <h4 class="text-subtitle-1 font-weight-bold mb-2">Description</h4>
                            <p class="text-body-2">{{ product.description }}</p>
                        </div>
                    </v-card-text>
                </v-card>

                <!-- Stock & Inventory -->
                <v-card class="mb-4 hover-card hover-tilt elevation-0" rounded="lg">
                    <v-card-title>
                        <v-icon start>mdi-package-variant</v-icon>
                        Stock & Inventory
                    </v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6">
                                <div class="d-flex align-center mb-3">
                                    <v-icon :color="stockColor" class="mr-2">{{ stockIcon }}</v-icon>
                                    <div>
                                        <div class="text-body-1 font-weight-medium" :class="stockTextColor">
                                            {{ stockText }}
                                        </div>
                                        <div class="text-caption text-medium-emphasis">
                                            Current Stock Level
                                        </div>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <div class="text-body-1 font-weight-medium">
                                    {{ product.reservedStock || 0 }} Reserved
                                </div>
                                <div class="text-caption text-medium-emphasis">
                                    Pending Orders
                                </div>
                            </v-col>
                        </v-row>
                        <v-progress-linear v-if="product.stock > 0" :model-value="stockPercentage" :color="stockColor"
                            height="8" rounded class="mt-3"></v-progress-linear>
                    </v-card-text>
                </v-card>

                <!-- Quick Stats -->
                <v-card class="hover-card hover-scale elevation-0" rounded="lg">
                    <v-card-title>
                        <v-icon start>mdi-chart-box</v-icon>
                        Quick Stats
                    </v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="6">
                                <v-card variant="tonal" color="primary" class="stat-card hover-bounce">
                                    <v-card-text class="text-center">
                                        <div class="text-h6 font-weight-bold">{{ product.viewCount || 0 }}</div>
                                        <div class="text-caption">Total Views</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="6">
                                <v-card variant="tonal" color="success" class="stat-card hover-bounce">
                                    <v-card-text class="text-center">
                                        <div class="text-h6 font-weight-bold">{{ product.price || 0 }}</div>
                                        <div class="text-caption">Total Sales</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="6">
                                <v-card variant="tonal" color="info" class="stat-card hover-bounce">
                                    <v-card-text class="text-center">
                                        <div class="text-h6 font-weight-bold">{{ formattedRevenue }}</div>
                                        <div class="text-caption">Revenue</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="6">
                                <v-card variant="tonal" color="warning" class="stat-card hover-bounce">
                                    <v-card-text class="text-center">
                                        <div class="text-h6 font-weight-bold">{{ product.wishlistCount || 0 }}</div>
                                        <div class="text-caption">Wishlisted</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>

                <!-- Features -->
                <v-card v-if="product.features && product.features.length" class="hover-card hover-pulse elevation-0" rounded="lg">
                    <v-card-title>
                        <v-icon start>mdi-star</v-icon>
                        Features
                    </v-card-title>
                    <v-card-text>
                        <div class="features-grid">
                            <v-chip v-for="feature in product.features" :key="feature" variant="outlined"
                                color="primary" size="small" class="ma-1 hover-chip">
                                <v-icon start>mdi-check-circle</v-icon>
                                {{ feature }}
                            </v-chip>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Snackbar for notifications -->
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" :timeout="3000" location="bottom">
            {{ snackbar.message }}
            <template v-slot:actions>
                <v-btn color="white" variant="text" @click="snackbar.show = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        </v-container>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

/**
 * Props Definition
 * 
 * @type {Object}
 * @property {Object} product - The product object
 * @property {string} product.id - The product ID
 * @property {string} product.name - The product name
 * @property {string} product.description - The product description
 * @property {string} product.imageUrl - The product image URL
 * @property {number} product.price - The product price
 * @property {number} product.originalPrice - The original product price
 * @property {string} product.category - The product category
 * @property {string} product.brand - The product brand
 * @property {number} product.rating - The product rating
 * @property {number} product.reviewCount - The number of reviews for the product
 * @property {number} product.stock - The current stock level
 * @property {number} product.maxStock - The maximum stock level
 * @property {number} product.reservedStock - The reserved stock level
 * @property {number} product.viewCount - The total view count
 * @property {number} product.salesCount - The total sales count
 * @property {number} product.wishlistCount - The total wishlist count
 * @property {string[]} product.features - An array of product features
 * @property {boolean} product.isNew - Indicates if the product is new
 * @property {boolean} product.onSale - Indicates if the product is on sale
 * 
 */
const props = defineProps({
    product: {
        type: Object,
        required: true,
    }
})

/**
 * Reactive State - Snackbar
 * 
 * @type {Object}
 * @property {boolean} show - Whether the snackbar is visible
 * @property {string} message - The message to display in the snackbar
 * @property {string} color - The color of the snackbar
 * 
 */
const snackbar = reactive({
    show: false,
    message: '',
    color: 'success'
})

/**
 * Computed Properties for Pricing
 * 
 * @type {Object}
 * @property {string} formattedPrice - The formatted price
 * @property {string} formattedOriginalPrice - The formatted original price
 *
 * 
 */
const formattedPrice = computed(() => {
    return formatPrice(props.product.price || 0)
})

/**
 * Format Original Price
 * 
 */
const formattedOriginalPrice = computed(() => {
    return formatPrice(props.product.originalPrice || 0)
})

/**
 * Format Revenue 
 * 
 * 
 */
const formattedRevenue = computed(() => {
    const revenue = (props.product.salesCount || 0) * (props.product.price || 0)
    return formatPrice(revenue)
})

/**
 * 
 * Computed Properties for Discount
 * 
 */
const hasDiscount = computed(() => {
    return props.product.originalPrice &&
        props.product.originalPrice > props.product.price
})

/**
 * Discount Percentage
 * 
 */
const discountPercentage = computed(() => {
    if (!hasDiscount.value) return 0
    const discount = ((props.product.originalPrice - props.product.price) / props.product.originalPrice) * 100
    return Math.round(discount)
})

/**
 * Computed Properties for Stock Management
 * 
 */
const stockColor = computed(() => {
    const stock = props.product.stock || 0
    if (stock === 0) return 'error'
    if (stock < 10) return 'warning'
    return 'success'
})

/**
 * Computed Stock Icon
 * 
 */
const stockIcon = computed(() => {
    const stock = props.product.stock || 0
    if (stock === 0) return 'mdi-alert-circle'
    if (stock < 10) return 'mdi-alert'
    return 'mdi-check-circle'
})

/**
 * Stock Text
 * 
 */
const stockText = computed(() => {
    const stock = props.product.stock || 0
    if (stock === 0) return 'Out of Stock'
    if (stock < 10) return `Low Stock (${stock} items)`
    return `In Stock (${stock} items)`
})

/**
 * Stock text color 
 * 
 */
const stockTextColor = computed(() => {
    const stock = props.product.stock || 0
    if (stock === 0) return 'text-error'
    if (stock < 10) return 'text-warning'
    return 'text-success'
})

/**
 * Stock percentage Computed
 * 
 */
const stockPercentage = computed(() => {
    const stock = props.product.stock || 0
    const maxStock = props.product.maxStock || 100
    return Math.min((stock / maxStock) * 100, 100)
})

/**
 * Utility Methods
 */
const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price || 0)
}

/**
 * Handle Call Back
 * 
 * 
 */
const handleCallBack = () => {
    router.visit(route('dashboard.products.index'))
}
</script>

<style scoped>

.image-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
}

.product-image {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.status-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 2;
}

.pricing-section {
    padding: 16px;
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 8px;
}

.features-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.v-card {
    border-radius: 12px;
    border: 1px solid #e0e0e0 !important;
    box-shadow: none !important;
}

.v-card:hover {
    border-color: #d0d0d0 !important;
}

.v-card-title {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    font-weight: 600;
}

/* Cool Hover Effects */
.hover-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

/* Lift Effect */
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2) !important;
}

.hover-lift:hover .product-image {
    transform: scale(1.02);
}

/* Glow Effect */
.hover-glow {
    position: relative;
    overflow: hidden;
}

.hover-glow::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
    z-index: 1;
}

.hover-glow:hover::before {
    left: 100%;
}

.hover-glow:hover {
    box-shadow: 0 0 30px rgba(25, 118, 210, 0.3) !important;
}

/* Tilt Effect */
.hover-tilt:hover {
    transform: perspective(1000px) rotateX(2deg) rotateY(-2deg) translateY(-4px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
}

/* Scale Effect */
.hover-scale:hover {
    transform: scale(1.02) translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

/* Pulse Effect */
.hover-pulse:hover {
    animation: pulse 0.6s ease-in-out;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.03);
    }

    100% {
        transform: scale(1);
    }
}

/* Stat Cards Hover */
.stat-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-bounce:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Feature Chips Hover */
.hover-chip {
    transition: all 0.2s ease;
    cursor: pointer;
}

.hover-chip:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive adjustments */
@media (max-width: 960px) {
    .admin-product-view {
        padding: 16px;
    }

    /* Reduce hover effects on mobile */
    .hover-card:hover {
        transform: translateY(-1px);
    }

    .hover-lift:hover {
        transform: translateY(-3px);
    }

    .hover-tilt:hover {
        transform: perspective(1000px) rotateX(1deg) rotateY(-1deg) translateY(-2px);
    }
}

@media (max-width: 600px) {
    .admin-product-view {
        padding: 12px;
    }

    .v-card-title .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    /* Disable complex hover effects on small screens */
    .hover-tilt:hover {
        transform: translateY(-2px);
    }

    .hover-glow::before {
        display: none;
    }
}

/* Add smooth transitions for all interactive elements */
* {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

/* Enhanced focus states for accessibility */
.hover-card:focus-visible {
    outline: 2px solid #1976d2;
    outline-offset: 2px;
}
</style>