/**
 * ProductResource
 * 
 * Transforms product data for frontend consumption
 * Handles data formatting, relationships, and computed properties
 */

export class ProductResource {
    constructor(product) {
        this.product = product
    }

    /**
     * Transform single product
     */
    static make(product) {
        if (!product) return null
        return new ProductResource(product).transform()
    }

    /**
     * Transform collection of products
     */
    static collection(products) {
        if (!Array.isArray(products)) return []
        return products.map(product => ProductResource.make(product))
    }

    /**
     * Transform paginated products
     */
    static paginated(paginatedData) {
        if (!paginatedData || !paginatedData.data) return paginatedData
        
        return {
            ...paginatedData,
            data: ProductResource.collection(paginatedData.data)
        }
    }

    /**
     * Main transformation method
     */
    transform() {
        return {
            // Basic properties
            id: this.product.id,
            name: this.product.name,
            description: this.product.description,
            price: this.formatPrice(this.product.price),
            quantity: this.product.quantity || 0,
            status: this.product.status || 'active',
            size: this.product.size,
            type: this.product.type,
            ingredients: this.product.ingredients,

            // Relationships
            category: this.transformCategory(this.product.category),
            category_id: this.product.category_id,

            // Computed properties
            imageUrl: this.getImageUrl(),
            formattedPrice: this.getFormattedPrice(),
            statusColor: this.getStatusColor(),
            stockStatus: this.getStockStatus(),
            isLowStock: this.isLowStock(),
            categoryName: this.getCategoryName(),

            // Metadata
            created_at: this.product.created_at,
            updated_at: this.product.updated_at,
            created_at_formatted: this.formatDate(this.product.created_at),
            updated_at_formatted: this.formatDate(this.product.updated_at),

            // Additional computed fields for UI
            displayName: this.getDisplayName(),
            shortDescription: this.getShortDescription(),
            badges: this.getBadges(),
            actions: this.getAvailableActions()
        }
    }

    /**
     * Format price for display
     */
    formatPrice(price) {
        if (!price) return '0.00'
        return parseFloat(price).toFixed(2)
    }

    /**
     * Get formatted price with currency
     */
    getFormattedPrice() {
        return `$${this.formatPrice(this.product.price)}`
    }

    /**
     * Transform category relationship
     */
    transformCategory(category) {
        if (!category) return null
        return {
            id: category.id,
            name: category.name,
            slug: category.slug || null,
            description: category.description || null
        }
    }

    /**
     * Get category name
     */
    getCategoryName() {
        return this.product.category?.name || 'Uncategorized'
    }

    /**
     * Get image URL
     */
    getImageUrl() {
        if (this.product.image) {
            // If it's a full URL, return as is
            if (this.product.image.startsWith('http')) {
                return this.product.image
            }
            // Otherwise, construct the URL
            return `/storage/products/${this.product.image}`
        }
        return null
    }

    /**
     * Get status color for UI
     */
    getStatusColor() {
        const colors = {
            'active': 'success',
            'inactive': 'error',
            'draft': 'warning',
            'archived': 'grey'
        }
        return colors[this.product.status] || 'grey'
    }

    /**
     * Get stock status
     */
    getStockStatus() {
        const quantity = this.product.quantity || 0
        
        if (quantity === 0) return 'out_of_stock'
        if (quantity <= 5) return 'low_stock'
        if (quantity <= 20) return 'medium_stock'
        return 'in_stock'
    }

    /**
     * Check if product is low stock
     */
    isLowStock() {
        return (this.product.quantity || 0) <= 5
    }

    /**
     * Format date for display
     */
    formatDate(dateString) {
        if (!dateString) return null
        
        try {
            const date = new Date(dateString)
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            })
        } catch (error) {
            return dateString
        }
    }

    /**
     * Get display name (with fallback)
     */
    getDisplayName() {
        return this.product.name || 'Unnamed Product'
    }

    /**
     * Get short description for lists
     */
    getShortDescription(length = 100) {
        const desc = this.product.description || 'No description available'
        return desc.length > length ? desc.substring(0, length) + '...' : desc
    }

    /**
     * Get badges for product display
     */
    getBadges() {
        const badges = []

        // Status badge
        badges.push({
            text: this.product.status || 'active',
            color: this.getStatusColor(),
            icon: this.product.status === 'active' ? 'mdi-check-circle' : 'mdi-close-circle'
        })

        // Stock badge
        if (this.isLowStock()) {
            badges.push({
                text: 'Low Stock',
                color: 'warning',
                icon: 'mdi-alert'
            })
        }

        // Price badge
        badges.push({
            text: this.getFormattedPrice(),
            color: 'primary',
            icon: 'mdi-currency-usd'
        })

        return badges
    }

    /**
     * Get available actions for this product
     */
    getAvailableActions() {
        const actions = [
            {
                name: 'view',
                label: 'View',
                icon: 'mdi-eye',
                color: 'primary',
                route: 'dashboard.products.show'
            },
            {
                name: 'edit',
                label: 'Edit',
                icon: 'mdi-pencil',
                color: 'warning',
                route: 'dashboard.products.edit'
            }
        ]

        // Only add delete if not a critical product
        if (this.product.status !== 'critical') {
            actions.push({
                name: 'delete',
                label: 'Delete',
                icon: 'mdi-delete',
                color: 'error',
                route: 'dashboard.products.confirm-delete'
            })
        }

        return actions
    }

    /**
     * Transform for form editing
     */
    toFormData() {
        return {
            name: this.product.name || '',
            description: this.product.description || '',
            price: this.product.price || '',
            category_id: this.product.category_id || null,
            quantity: this.product.quantity || '',
            status: this.product.status || 'active',
            size: this.product.size || '',
            type: this.product.type || '',
            ingredients: this.product.ingredients || '',
            image: null // Always null for file inputs
        }
    }

    /**
     * Transform for API response
     */
    toArray() {
        return this.transform()
    }

    /**
     * Transform for JSON serialization
     */
    toJSON() {
        return this.transform()
    }
}

export default ProductResource