import * as yup from 'yup'

/**
 * Category validation schemas using Yup
 * These schemas define validation rules for category forms
 */

// Base category schema with common fields
const baseCategorySchema = {
    name: yup
        .string()
        .required('Category name is required')
        .max(255, 'Category name cannot exceed 255 characters')
        .trim(),
    
    description: yup
        .string()
        .nullable()
        .max(1000, 'Description cannot exceed 1000 characters')
        .trim(),
    
    type: yup
        .string()
        .nullable()
        .max(100, 'Category type cannot exceed 100 characters')
        .trim(),
    
    menu_id: yup
        .number()
        .nullable()
        .positive('Menu ID must be a positive number')
        .integer('Menu ID must be an integer'),
    
    price_sale: yup
        .number()
        .nullable()
        .min(0, 'Price cannot be negative')
        .transform((value, originalValue) => {
            // Handle empty string or null values
            if (originalValue === '' || originalValue === null || originalValue === undefined) {
                return null
            }
            return value
        }),
    
    status: yup
        .string()
        .nullable()
        .oneOf(['active', 'inactive', 'draft'], 'Status must be one of: active, inactive, draft')
        .default('active'),
    
    imageUrl: yup
        .string()
        .nullable()
        .max(500, 'Image URL cannot exceed 500 characters')
        .url('Please enter a valid URL')
        .trim()
}

// Create category schema (for new categories)
export const createCategorySchema = yup.object().shape({
    ...baseCategorySchema
})

// Edit category schema (for existing categories)
export const editCategorySchema = yup.object().shape({
    ...baseCategorySchema,
    id: yup
        .number()
        .required('Category ID is required')
        .positive('Category ID must be a positive number')
        .integer('Category ID must be an integer')
})

// Quick create schema (minimal fields for quick category creation)
export const quickCreateCategorySchema = yup.object().shape({
    name: baseCategorySchema.name,
    description: baseCategorySchema.description,
    status: baseCategorySchema.status
})

// Bulk import schema (for importing multiple categories)
export const bulkImportCategorySchema = yup.array().of(
    yup.object().shape({
        ...baseCategorySchema,
        // Make name required for bulk import
        name: baseCategorySchema.name.required('Category name is required for import')
    })
)

// Search/filter schema
export const categoryFilterSchema = yup.object().shape({
    search: yup
        .string()
        .nullable()
        .trim(),
    
    status: yup
        .string()
        .nullable()
        .oneOf(['active', 'inactive', 'draft'], 'Invalid status filter'),
    
    menu_id: yup
        .number()
        .nullable()
        .positive('Menu ID must be a positive number')
        .integer('Menu ID must be an integer'),
    
    sort: yup
        .string()
        .nullable()
        .oneOf(['name', 'name_desc', 'created_at', 'created_at_asc', 'products_count'], 'Invalid sort option')
        .default('created_at'),
    
    direction: yup
        .string()
        .nullable()
        .oneOf(['asc', 'desc'], 'Sort direction must be asc or desc')
        .default('desc')
})

// Export commonly used field schemas for reuse
export const categoryFieldSchemas = {
    name: baseCategorySchema.name,
    description: baseCategorySchema.description,
    type: baseCategorySchema.type,
    menu_id: baseCategorySchema.menu_id,
    price_sale: baseCategorySchema.price_sale,
    status: baseCategorySchema.status,
    imageUrl: baseCategorySchema.imageUrl
}

// Validation helper functions
export const validateCategoryName = async (name) => {
    try {
        await categoryFieldSchemas.name.validate(name)
        return { isValid: true, error: null }
    } catch (error) {
        return { isValid: false, error: error.message }
    }
}

export const validateCategoryData = async (data, schema = createCategorySchema) => {
    try {
        const validatedData = await schema.validate(data, { 
            abortEarly: false,
            stripUnknown: true 
        })
        return { isValid: true, data: validatedData, errors: {} }
    } catch (error) {
        const errors = {}
        error.inner.forEach(err => {
            errors[err.path] = err.message
        })
        return { isValid: false, data: null, errors }
    }
}

export default {
    createCategorySchema,
    editCategorySchema,
    quickCreateCategorySchema,
    bulkImportCategorySchema,
    categoryFilterSchema,
    categoryFieldSchemas,
    validateCategoryName,
    validateCategoryData
}