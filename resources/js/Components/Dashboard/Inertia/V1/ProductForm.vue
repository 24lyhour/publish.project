<template>
    <div class="space-y-6">
        <!-- Basic Information Section -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <Info class="w-5 h-5 text-blue-600" />
                <h4 class="text-lg font-semibold text-gray-900">{{ __("Basic Information") }}</h4>
            </div>
            
            <div class="space-y-4">
                <FormField name="name">
                    <FormItem>
                        <FormLabel>{{ __('Product Name') }} <span class="text-red-500">*</span></FormLabel>
                        <FormControl>
                            <Input
                                v-model="formData.name"
                                :placeholder="__('Enter a descriptive product name')"
                                :class="{ 'border-red-500': errors?.name }"
                                @input="emitUpdate" />
                        </FormControl>
                        <FormMessage v-if="errors?.name" class="text-red-500 text-sm">
                            {{ errors.name }}
                        </FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="description">
                    <FormItem>
                        <FormLabel>{{ __('Product Description') }} <span class="text-red-500">*</span></FormLabel>
                        <FormControl>
                            <Textarea
                                v-model="formData.description"
                                :placeholder="__('Describe your product in detail...')"
                                rows="4"
                                :class="{ 'border-red-500': errors?.description }"
                                @input="emitUpdate" />
                        </FormControl>
                        <FormMessage v-if="errors?.description" class="text-red-500 text-sm">
                            {{ errors.description }}
                        </FormMessage>
                    </FormItem>
                </FormField>
            </div>
        </div>

        <Separator />

        <!-- Pricing & Details Section -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <DollarSign class="w-5 h-5 text-green-600" />
                <h4 class="text-lg font-semibold text-gray-900">{{ __("Pricing & Details") }}</h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField name="price">
                    <FormItem>
                        <FormLabel>{{ __('Price (USD)') }} <span class="text-red-500">*</span></FormLabel>
                        <FormControl>
                            <Input
                                v-model="formData.price"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                :class="{ 'border-red-500': errors?.price }"
                                @input="emitUpdate" />
                        </FormControl>
                        <FormMessage v-if="errors?.price" class="text-red-500 text-sm">
                            {{ errors.price }}
                        </FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="quantity">
                    <FormItem>
                        <FormLabel>{{ __('Stock Quantity') }}</FormLabel>
                        <FormControl>
                            <Input
                                v-model="formData.quantity"
                                type="number"
                                placeholder="0"
                                :class="{ 'border-red-500': errors?.quantity }"
                                @input="emitUpdate" />
                        </FormControl>
                        <FormMessage v-if="errors?.quantity" class="text-red-500 text-sm">
                            {{ errors.quantity }}
                        </FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="category_id">
                    <FormItem>
                        <FormLabel>{{ __('Category') }}</FormLabel>
                        <Select v-model="formData.category_id" @update:model-value="emitUpdate">
                            <FormControl>
                                <SelectTrigger :class="{ 'border-red-500': errors?.category_id }">
                                    <SelectValue placeholder="Select a category" />
                                </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                                <SelectItem 
                                    v-for="category in categories" 
                                    :key="category.id" 
                                    :value="category.id">
                                    {{ category.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <FormMessage v-if="errors?.category_id" class="text-red-500 text-sm">
                            {{ errors.category_id }}
                        </FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="status">
                    <FormItem>
                        <FormLabel>{{ __('Status') }}</FormLabel>
                        <Select v-model="formData.status" @update:model-value="emitUpdate">
                            <FormControl>
                                <SelectTrigger :class="{ 'border-red-500': errors?.status }">
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                                <SelectItem value="active">{{ __('Active') }}</SelectItem>
                                <SelectItem value="inactive">{{ __('Inactive') }}</SelectItem>
                                <SelectItem value="draft">{{ __('Draft') }}</SelectItem>
                            </SelectContent>
                        </Select>
                        <FormMessage v-if="errors?.status" class="text-red-500 text-sm">
                            {{ errors.status }}
                        </FormMessage>
                    </FormItem>
                </FormField>
            </div>
        </div>

        <Separator />

        <!-- Additional Details Section -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <Settings class="w-5 h-5 text-yellow-600" />
                <h4 class="text-lg font-semibold text-gray-900">{{ __("Additional Details") }}</h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField name="size">
                    <FormItem>
                        <FormLabel>{{ __('Size') }}</FormLabel>
                        <FormControl>
                            <Input
                                v-model="formData.size"
                                :placeholder="__('e.g., Large, 500ml, XL')"
                                :class="{ 'border-red-500': errors?.size }"
                                @input="emitUpdate" />
                        </FormControl>
                        <FormMessage v-if="errors?.size" class="text-red-500 text-sm">
                            {{ errors.size }}
                        </FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="type">
                    <FormItem>
                        <FormLabel>{{ __('Type') }}</FormLabel>
                        <FormControl>
                            <Input
                                v-model="formData.type"
                                :placeholder="__('e.g., Premium, Standard')"
                                :class="{ 'border-red-500': errors?.type }"
                                @input="emitUpdate" />
                        </FormControl>
                        <FormMessage v-if="errors?.type" class="text-red-500 text-sm">
                            {{ errors.type }}
                        </FormMessage>
                    </FormItem>
                </FormField>
            </div>

            <!-- Image Upload -->
            <FormField v-if="showImageUpload" name="image">
                <FormItem>
                    <FormLabel>{{ __('Product Image') }}</FormLabel>
                    <FormControl>
                        <div class="space-y-4">
                            <Input
                                type="file"
                                accept="image/*"
                                :class="{ 'border-red-500': errors?.image }"
                                @change="handleImageChange" />
                            
                            <!-- Image Preview -->
                            <div v-if="imagePreview" class="relative inline-block">
                                <img 
                                    :src="imagePreview" 
                                    alt="Preview"
                                    class="w-48 h-48 object-cover rounded-lg border border-gray-200" />
                                <Button
                                    type="button"
                                    variant="destructive"
                                    size="sm"
                                    class="absolute top-2 right-2"
                                    @click="clearImage">
                                    <X class="w-4 h-4" />
                                </Button>
                            </div>
                        </div>
                    </FormControl>
                    <FormMessage v-if="errors?.image" class="text-red-500 text-sm">
                        {{ errors.image }}
                    </FormMessage>
                </FormItem>
            </FormField>

            <!-- Ingredients -->
            <FormField name="ingredients">
                <FormItem>
                    <FormLabel>{{ __('Ingredients') }}</FormLabel>
                    <FormControl>
                        <Textarea
                            v-model="formData.ingredients"
                            :placeholder="__('List ingredients (optional)')"
                            rows="2"
                            :class="{ 'border-red-500': errors?.ingredients }"
                            @input="emitUpdate" />
                    </FormControl>
                    <FormMessage v-if="errors?.ingredients" class="text-red-500 text-sm">
                        {{ errors.ingredients }}
                    </FormMessage>
                </FormItem>
            </FormField>
        </div>

        <!-- Custom Fields Slot -->
        <slot name="additional-fields" :form="formData" :errors="errors"></slot>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { Separator } from '@/Components/ui/separator'
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/Components/ui/form'
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select'
import { Info, DollarSign, Settings, X } from 'lucide-vue-next'

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    },
    categories: {
        type: Array,
        default: () => []
    },
    product: {
        type: Object,
        default: null
    },
    showImageUpload: {
        type: Boolean,
        default: true
    },
    processing: {
        type: Boolean,
        default: false
    },
    mode: {
        type: String,
        default: 'create',
        validator: value => ['create', 'edit'].includes(value)
    }
})

const emit = defineEmits(['update:modelValue', 'imageChanged'])

// Component state
const formData = ref({ ...props.modelValue })
const imagePreview = ref(null)

// Methods
const emitUpdate = () => {
    emit('update:modelValue', formData.value)
}

const handleImageChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        formData.value.image = file
        // Create preview URL for new file
        if (imagePreview.value && formData.value.image instanceof File) {
            URL.revokeObjectURL(imagePreview.value)
        }
        imagePreview.value = URL.createObjectURL(file)
        emit('imageChanged', { file, previewUrl: imagePreview.value })
    } else {
        formData.value.image = null
        if (imagePreview.value) {
            URL.revokeObjectURL(imagePreview.value)
        }
        imagePreview.value = null
        emit('imageChanged', { file: null, previewUrl: null })
    }
    emitUpdate()
}

const clearImage = () => {
    if (imagePreview.value && formData.value.image instanceof File) {
        URL.revokeObjectURL(imagePreview.value)
    }
    formData.value.image = null
    imagePreview.value = null
    emit('imageChanged', { file: null, previewUrl: null })
    emitUpdate()
}

// Translation helper
const __ = (key) => {
    const translations = {
        'Basic Information': 'Basic Information',
        'Product Name': 'Product Name',
        'Enter a descriptive product name': 'Enter a descriptive product name',
        'Product Description': 'Product Description',
        'Describe your product in detail...': 'Describe your product in detail...',
        'Pricing & Details': 'Pricing & Details',
        'Price (USD)': 'Price (USD)',
        'Stock Quantity': 'Stock Quantity',
        'Category': 'Category',
        'Status': 'Status',
        'Additional Details': 'Additional Details',
        'Size': 'Size',
        'e.g., Large, 500ml, XL': 'e.g., Large, 500ml, XL',
        'Type': 'Type',
        'e.g., Premium, Standard': 'e.g., Premium, Standard',
        'Product Image': 'Product Image',
        'Ingredients': 'Ingredients',
        'List ingredients (optional)': 'List ingredients (optional)',
        'Active': 'Active',
        'Inactive': 'Inactive',
        'Draft': 'Draft'
    }
    return translations[key] || key
}

// Initialize image preview for existing product
onMounted(() => {
    if (props.product && props.product.imageUrl) {
        imagePreview.value = props.product.imageUrl
    }
})

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
    formData.value = { ...newValue }
}, { deep: true })

// Watch for changes in formData and sync back
watch(formData, () => {
    emitUpdate()
}, { deep: true })
</script>