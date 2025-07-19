/**
 * Product Validation Schemas
 * 
 * Using Yup for form validation
 * https://github.com/jquense/yup
 */

import * as yup from 'yup'

// File validation helper
const imageValidation = yup
    .mixed()
    .nullable()
    .test('fileSize', 'File size is too large (max 5MB)', (value) => {
        if (!value) return true // Allow null/undefined
        if (typeof value === 'string') return true // Allow URL strings
        return value.size <= 5 * 1024 * 1024 // 5MB
    })
    .test('fileFormat', 'Unsupported file format. Only images are allowed.', (value) => {
        if (!value) return true // Allow null/undefined
        if (typeof value === 'string') return true // Allow URL strings
        return ['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(value.type)
    })

// Price validation helper
const priceValidation = yup
    .number()
    .typeError('Price must be a valid number')
    .positive('Price must be positive')
    .max(999999.99, 'Price is too high')
    .test('decimal', 'Price can have at most 2 decimal places', (value) => {
        if (!value) return true
        return /^\d+(\.\d{1,2})?$/.test(value.toString())
    })

// Base product schema
const baseProductSchema = {
    name: yup
        .string()
        .required('Product name is required')
        .min(2, 'Name must be at least 2 characters')
        .max(255, 'Name cannot exceed 255 characters')
        .trim(),

    description: yup
        .string()
        .required('Description is required')
        .min(10, 'Description must be at least 10 characters')
        .max(1000, 'Description cannot exceed 1000 characters')
        .trim(),

    price: priceValidation.required('Price is required'),

    category_id: yup
        .number()
        .nullable()
        .integer('Category must be a valid selection')
        .positive('Category must be a valid selection'),

    quantity: yup
        .number()
        .nullable()
        .integer('Quantity must be a whole number')
        .min(0, 'Quantity cannot be negative')
        .max(999999, 'Quantity is too high'),

    status: yup
        .string()
        .oneOf(['active', 'inactive', 'draft'], 'Status must be active, inactive, or draft')
        .default('active'),

    size: yup
        .string()
        .nullable()
        .max(100, 'Size cannot exceed 100 characters')
        .trim(),

    type: yup
        .string()
        .nullable()
        .max(100, 'Type cannot exceed 100 characters')
        .trim(),

    ingredients: yup
        .string()
        .nullable()
        .max(1000, 'Ingredients cannot exceed 1000 characters')
        .trim()
}

/**
 * Create Product Validation Schema
 */
export const createProductSchema = yup.object({
    ...baseProductSchema,
    image: imageValidation
})

/**
 * Edit Product Validation Schema
 */
export const editProductSchema = yup.object({
    ...baseProductSchema,
    image: imageValidation
})

/**
 * Quick Product Validation Schema (for inline editing)
 */
export const quickEditProductSchema = yup.object({
    name: baseProductSchema.name,
    price: baseProductSchema.price,
    quantity: baseProductSchema.quantity,
    status: baseProductSchema.status
})

/**
 * Product Search/Filter Validation Schema
 */
export const productFilterSchema = yup.object({
    search: yup
        .string()
        .nullable()
        .max(255, 'Search term too long'),

    category_id: yup
        .number()
        .nullable()
        .integer('Invalid category')
        .positive('Invalid category'),

    status: yup
        .string()
        .nullable()
        .oneOf(['active', 'inactive', 'draft'], 'Invalid status'),

    price_min: yup
        .number()
        .nullable()
        .min(0, 'Minimum price cannot be negative'),

    price_max: yup
        .number()
        .nullable()
        .min(0, 'Maximum price cannot be negative')
        .when('price_min', (priceMin, schema) => {
            return priceMin
                ? schema.min(priceMin, 'Maximum price must be greater than minimum price')
                : schema
        }),

    sort: yup
        .string()
        .nullable()
        .oneOf(['name', 'price', 'created_at', 'updated_at'], 'Invalid sort field'),

    direction: yup
        .string()
        .nullable()
        .oneOf(['asc', 'desc'], 'Invalid sort direction')
})

/**
 * Bulk Product Operations Schema
 */
export const bulkProductSchema = yup.object({
    product_ids: yup
        .array()
        .of(yup.number().positive().integer())
        .min(1, 'Select at least one product')
        .required('Product selection is required'),

    action: yup
        .string()
        .oneOf(['delete', 'activate', 'deactivate', 'update_category'], 'Invalid bulk action')
        .required('Action is required'),

    category_id: yup
        .number()
        .nullable()
        .when('action', {
            is: 'update_category',
            then: (schema) => schema.required('Category is required for this action'),
            otherwise: (schema) => schema.nullable()
        })
})

/**
 * Product Import Schema
 */
export const productImportSchema = yup.object({
    file: yup
        .mixed()
        .required('Import file is required')
        .test('fileFormat', 'File must be CSV or Excel format', (value) => {
            if (!value) return false
            const validTypes = [
                'text/csv',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
            return validTypes.includes(value.type)
        })
        .test('fileSize', 'File size cannot exceed 10MB', (value) => {
            if (!value) return false
            return value.size <= 10 * 1024 * 1024 // 10MB
        }),

    skip_header: yup
        .boolean()
        .default(true),

    update_existing: yup
        .boolean()
        .default(false)
})

/**
 * Get validation schema by type
 */
export const getProductSchema = (type = 'create') => {
    const schemas = {
        create: createProductSchema,
        edit: editProductSchema,
        quickEdit: quickEditProductSchema,
        filter: productFilterSchema,
        bulk: bulkProductSchema,
        import: productImportSchema
    }

    return schemas[type] || createProductSchema
}

/**
 * Validate product data
 */
export const validateProduct = async (data, type = 'create') => {
    const schema = getProductSchema(type)
    
    try {
        const validData = await schema.validate(data, { 
            abortEarly: false,
            stripUnknown: true 
        })
        return { valid: true, data: validData, errors: {} }
    } catch (error) {
        const errors = {}
        
        if (error.inner) {
            error.inner.forEach(err => {
                errors[err.path] = err.message
            })
        }
        
        return { valid: false, data: null, errors }
    }
}

/**
 * Get field-specific validation rules (for real-time validation)
 */
export const getFieldValidation = (fieldName) => {
    const fieldValidations = {
        name: baseProductSchema.name,
        description: baseProductSchema.description,
        price: baseProductSchema.price,
        category_id: baseProductSchema.category_id,
        quantity: baseProductSchema.quantity,
        status: baseProductSchema.status,
        size: baseProductSchema.size,
        type: baseProductSchema.type,
        ingredients: baseProductSchema.ingredients,
        image: imageValidation
    }

    return fieldValidations[fieldName] || yup.mixed()
}

export default {
    createProductSchema,
    editProductSchema,
    quickEditProductSchema,
    productFilterSchema,
    bulkProductSchema,
    productImportSchema,
    getProductSchema,
    validateProduct,
    getFieldValidation
}