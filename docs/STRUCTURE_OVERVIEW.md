# Dashboard Structure Overview

## 📁 New Folder Structure

### Dashboard/Inertia/V1/ Organization
```
resources/js/Pages/Dashboard/Inertia/V1/
├── Modals/
│   └── FormSakal/
│       ├── SakalModal.vue           # Base modal component (5 types)
│       ├── SakalFormModal.vue       # Form modal with Inertia integration
│       ├── ProductFormModal.vue     # Product-specific modal
│       ├── CategoryFormModal.vue    # Category modal with slots
│       ├── FlexibleModal.vue        # Generic modal with full slots
│       └── ProductFields/           # Modular field components
│           ├── ProductBasicFields.vue
│           ├── ProductDetailFields.vue
│           ├── ProductMediaFields.vue
│           └── ProductPreview.vue
└── ProductForm/
    ├── CreateForm.vue              # Step-by-step create form
    └── EditForm.vue                # Edit form with preview
```

## 🎯 Usage Examples

### 1. Simple Sakal Modal Usage
```vue
<SakalModal 
    :show="true" 
    title="Confirm Action" 
    type="warning">
    <p>Are you sure?</p>
</SakalModal>
```

### 2. Sakal Form Modal Usage
```vue
<SakalFormModal
    :show="true"
    title="Create Item"
    method="post"
    action="/api/items"
    :form-data="{ name: '', description: '' }">
    
    <template #default="{ form, errors }">
        <v-text-field v-model="form.name" :error-messages="errors.name" />
    </template>
</SakalFormModal>
```

### 3. Product Form in Create Page
```vue
<SakalFormModal :show="true" title="Create Product">
    <template #default="{ form, errors }">
        <CreateForm :form="form" :errors="errors" :categories="categories" />
    </template>
</SakalFormModal>
```

## 🛣️ Route Structure

### Grouped Routes with Prefixes
```
/dashboard/management/products/          # Products index
/dashboard/management/products/create    # Create product
/dashboard/management/products/{id}      # Show product
/dashboard/management/products/{id}/edit # Edit product
/dashboard/management/products/modal/create           # Modal create
/dashboard/management/products/{id}/modal/edit        # Modal edit
/dashboard/management/products/{id}/delete            # Delete confirmation

/dashboard/management/categories/        # Categories management
/dashboard/management/restaurants/       # Restaurants management
/dashboard/management/menus/            # Menus management
```

### Route Names
```php
dashboard.products.index
dashboard.products.create
dashboard.products.store
dashboard.products.show
dashboard.products.edit
dashboard.products.update
dashboard.products.destroy
dashboard.products.modal.create
dashboard.products.modal.edit
dashboard.products.confirm-delete
```

## 🔧 Controller Updates

### ProductController Modal Methods
```php
public function createModal()
{
    return Inertia::modal('Dashboard/Inertia/V1/Modals/FormSakal/ProductFormModal')
        ->with([
            'mode' => 'create',
            'categories' => Category::all(['id', 'name']),
            'method' => 'post',
            'action' => route('dashboard.products.store'),
        ]);
}

public function editModal(Product $product)
{
    return Inertia::modal('Dashboard/Inertia/V1/Modals/FormSakal/ProductFormModal')
        ->with([
            'mode' => 'edit',
            'product' => $product,
            'categories' => Category::all(['id', 'name']),
            'method' => 'patch',
            'action' => route('dashboard.products.update', $product),
        ]);
}
```

## 🎨 Design Features

### Modal Types
- **default**: Standard modal
- **success**: Green gradient with success icons
- **warning**: Orange gradient with warning icons
- **error**: Red gradient with error icons
- **info**: Blue gradient with info icons

### Form Features
- **Step-by-step wizard** for create mode
- **Live preview** in edit mode
- **Progress tracking** with completion percentage
- **Validation integration** with Inertia forms
- **Image upload** with preview
- **Responsive design** for all screen sizes

### Slot System
- **#header**: Custom header content
- **#content**: Main modal content
- **#footer**: Custom action buttons
- **#additional-fields**: Extend forms dynamically

## 🚀 Benefits

1. **Reusability**: Components can be used across the entire application
2. **Modularity**: Field components can be mixed and matched
3. **Consistency**: All modals follow the same design patterns
4. **Flexibility**: Slot system allows for complete customization
5. **Organization**: Clear folder structure for easy maintenance
6. **Performance**: Modular loading and clean component separation

## 📋 Migration Summary

### What Was Moved
- ✅ Modals moved from `Components/Modals/` to `Dashboard/Inertia/V1/Modals/FormSakal/`
- ✅ Created modular ProductForm components
- ✅ Updated Create.vue to use SakalModal + ProductForm approach
- ✅ Restructured routes with grouped prefixes
- ✅ Updated all controller references
- ✅ Cleaned up old modal files

### Route Changes
- ✅ Products routes now under `dashboard.products.*`
- ✅ Modal routes added for seamless Inertia modal integration
- ✅ Grouped management routes with proper prefixes
- ✅ All routes secured under auth middleware

This structure provides maximum flexibility, reusability, and maintainability for your dashboard system!