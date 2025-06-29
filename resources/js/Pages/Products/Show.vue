<template>
    <div v-if="product && product.id">
        <v-card class="mx-auto product-card" :max-width="cardWidth" elevation="8" rounded="lg" @click="handleCardClick"
            :class="{ 'card-hover': isHoverable }">
            <!-- Product Image with Badges -->
            <div class="image-container">
                <v-img :src="product.imageUrl" :height="imageHeight" cover :alt="`Image of ${product.name}`">
                    <template v-slot:placeholder>
                        <div class="d-flex align-center justify-center fill-height">
                            <v-progress-circular color="grey-lighten-4" indeterminate></v-progress-circular>
                        </div>
                    </template>
                </v-img>

                <!-- Status Badges -->
                <div class="badges-container">
                    <v-chip v-if="product.isNew" color="success" size="small" class="badge-chip">
                        NEW
                    </v-chip>
                    <v-chip v-if="product.onSale" color="error" size="small" class="badge-chip">
                        SALE
                    </v-chip>
                    <v-chip v-if="product.stock <= 5 && product.stock > 0" color="warning" size="small"
                        class="badge-chip">
                        LOW STOCK
                    </v-chip>
                    <v-chip v-if="product.stock === 0" color="error" variant="elevated" size="small" class="badge-chip">
                        OUT OF STOCK
                    </v-chip>
                </div>

                <!-- Wishlist/Favorite Button -->
                <v-btn v-if="showWishlist" icon class="wishlist-btn" @click.stop="toggleWishlist"
                    :color="isWishlisted ? 'error' : 'white'">
                    <v-icon>{{ isWishlisted ? 'mdi-heart' : 'mdi-heart-outline' }}</v-icon>
                </v-btn>
            </div>

            <!-- Product Info Header -->
            <div class="d-flex justify-space-between align-start pa-5">
                <div class="product-info">
                    <v-card-title class="pa-0 text-h5 font-weight-bold">
                        {{ product.name }}
                    </v-card-title>

                    <!-- Category/Brand -->
                    <div v-if="product.category || product.brand" class="mt-1">
                        <v-chip v-if="product.category" size="x-small" variant="outlined" class="mr-2">
                            {{ product.category }}
                        </v-chip>
                        <span v-if="product.brand" class="text-caption text-grey">
                            by {{ product.brand }}
                        </span>
                    </div>

                    <!-- Rating -->
                    <div v-if="product.rating" class="d-flex align-center mt-2">
                        <v-rating :model-value="product.rating" color="amber" density="compact" size="small"
                            readonly></v-rating>
                        <span class="text-caption text-grey ml-2">
                            ({{ product.reviewCount || 0 }} reviews)
                        </span>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="price-section ml-4">
                    <div v-if="product.originalPrice && product.originalPrice > product.price"
                        class="text-decoration-line-through text-caption text-grey">
                        {{ formatPrice(product.originalPrice) }}
                    </div>
                    <v-chip color="primary" variant="elevated" size="large">
                        {{ formattedPrice }}
                    </v-chip>
                    <div v-if="product.originalPrice && product.originalPrice > product.price"
                        class="text-success text-caption mt-1">
                        Save {{ formatPrice(product.originalPrice - product.price) }}
                    </div>
                </div>
            </div>

            <v-divider class="mx-4 mb-2"></v-divider>

            <!-- Product Description -->
            <v-card-text class="text-subtitle-1">
                {{ truncateDescription ? truncatedDescription : product.description }}
                <v-btn v-if="product.description && product.description.length > 100 && truncateDescription"
                    variant="text" size="small" @click="showFullDescription = !showFullDescription" class="pa-0 ml-2">
                    {{ showFullDescription ? 'Show Less' : 'Read More' }}
                </v-btn>
            </v-card-text>

            <!-- Product Features/Tags -->
            <div v-if="product.features && product.features.length" class="px-5 pb-3">
                <div class="text-caption text-grey mb-2">Features:</div>
                <div class="d-flex flex-wrap gap-1">
                    <v-chip v-for="feature in product.features.slice(0, 3)" :key="feature" size="x-small"
                        variant="outlined" color="primary">
                        {{ feature }}
                    </v-chip>
                    <v-chip v-if="product.features.length > 3" size="x-small" variant="outlined" color="primary">
                        +{{ product.features.length - 3 }} more
                    </v-chip>
                </div>
            </div>

            <!-- Action Buttons -->
            <v-card-actions v-if="showActions" class="px-5 pb-5">
                <v-btn color="primary" variant="elevated" block @click.stop="handleAddToCart"
                    :disabled="product.stock === 0" :loading="isAddingToCart">
                    <v-icon left>mdi-cart-plus</v-icon>
                    {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
                </v-btn>

                <v-btn v-if="showQuickView" variant="outlined" class="ml-2" @click.stop="handleQuickView">
                    <v-icon>mdi-eye</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>

    <!-- Fallback State -->
    <div v-else class="text-center pa-10">
        <v-icon size="60" color="grey-lighten-1">mdi-package-variant-closed</v-icon>
        <p class="text-h6 text-grey mt-4">Product Information Not Available</p>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

/**
 * Props Define Object
 * 
 */
const props = defineProps({
    product: {
        type: Object,
        required: true,
        default: () => ({
            id: null,
            name: 'Loading...',
            description: 'Please wait while we load the product details.',
            price: 0,
            originalPrice: null,
            imageUrl: '',
            category: null,
            brand: null,
            rating: null,
            reviewCount: 0,
            stock: 0,
            isNew: false,
            onSale: false,
            features: []
        })
    },
    cardWidth: {
        type: [String, Number],
        default: 550
    },
    imageHeight: {
        type: String,
        default: '300px'
    },
    isHoverable: {
        type: Boolean,
        default: true
    },
    showWishlist: {
        type: Boolean,
        default: true
    },
    showActions: {
        type: Boolean,
        default: true
    },
    showQuickView: {
        type: Boolean,
        default: true
    },
    truncateDescription: {
        type: Boolean,
        default: true
    }
});

/**
 * Emit define
 * 
 */
const emit = defineEmits([
    'click',
    'add-to-cart',
    'toggle-wishlist',
    'quick-view'
]);

/**
 * Reactive 
 * 
 */
const isWishlisted = ref(false);
const isAddingToCart = ref(false);
const showFullDescription = ref(false);

/**
 * Format Price
 * 
 * Computed Price
 * 
 */
const formattedPrice = computed(() => {
    if (typeof props.product.price !== 'number') {
        return '$0.00';
    }
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(props.product.price);
});

/**
 * Truncated Description
 * 
 */
const truncatedDescription = computed(() => {
    if (!props.product.description) return '';
    if (showFullDescription.value || props.product.description.length <= 100) {
        return props.product.description;
    }
    return props.product.description.substring(0, 100) + '...';
});

/**
 * Format Price
 * 
 * @param price 
 * 
 */
const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

/**
 * Handle Card Click
 * 
 */
const handleCardClick = () => {
    emit('click', props.product);
};

const handleAddToCart = async () => {
    isAddingToCart.value = true;
    try {
        emit('add-to-cart', props.product);
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000));
    } finally {
        isAddingToCart.value = false;
    }
};

/**
 * Toggle Wishlist
 * 
 */
const toggleWishlist = () => {
    isWishlisted.value = !isWishlisted.value;
    emit('toggle-wishlist', {
        product: props.product,
        isWishlisted: isWishlisted.value
    });
};

/**
 * Handle Quick View
 * 
 * @param product 
 * 
 */
const handleQuickView = () => {
    emit('quick-view', props.product);
};
</script>

<style scoped>
.product-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.image-container {
    position: relative;
}

.badges-container {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.badge-chip {
    font-weight: bold;
    font-size: 0.7rem;
}

.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
}

.product-info {
    flex: 1;
    min-width: 0;
}

.price-section {
    text-align: right;
    flex-shrink: 0;
}

.v-card-title {
    white-space: normal;
    line-height: 1.3;
}

.v-card-text {
    line-height: 1.75;
    color: #333;
}

.gap-1 {
    gap: 4px;
}
</style>