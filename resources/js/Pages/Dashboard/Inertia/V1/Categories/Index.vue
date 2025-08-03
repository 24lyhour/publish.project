<template>
    <AuthenticatedLayout title="Categories">
        <div class="min-h-screen bg-gray-50 p-6">
            <!-- Modern Header -->
            <div class="mb-8">
                <Card class="border-0 shadow-sm">
                    <CardContent class="p-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-6">
                                <div class="bg-primary/10 p-4 rounded-xl">
                                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                                    <p class="text-gray-600 mt-1">Organize your products with categories</p>
                                </div>
                            </div>
                            <Button @click="createCategory" size="lg" class="gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Category
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash.success || $page.props.flash.error" class="mb-6">
                <div v-if="$page.props.flash.success"
                    class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash.error" class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">
                    {{ $page.props.flash.error }}
                </div>
            </div>

            <!-- Statistics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-blue-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ categories.data?.length || 0 }}</div>
                                <div class="text-sm text-gray-600">Total Categories</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-green-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ activeCategories }}</div>
                                <div class="text-sm text-gray-600">Active Categories</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-yellow-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ draftCategories }}</div>
                                <div class="text-sm text-gray-600">Draft Categories</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-purple-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ totalProducts }}</div>
                                <div class="text-sm text-gray-600">Total Products</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filters & Search -->
            <Card class="mb-6">
                <CardContent class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <Input v-model="search" placeholder="Search categories..." @keyup.enter="filterCategories"
                                class="w-full" />
                        </div>
                        <div>
                            <Select v-model="selectedStatus" @update:model-value="filterCategories">
                                <SelectTrigger>
                                    <SelectValue placeholder="Filter by Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="active">Active</SelectItem>
                                    <SelectItem value="inactive">Inactive</SelectItem>
                                    <SelectItem value="draft">Draft</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Select v-model="selectedSort" @update:model-value="filterCategories">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sort by" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="name">Name (A-Z)</SelectItem>
                                    <SelectItem value="name_desc">Name (Z-A)</SelectItem>
                                    <SelectItem value="created_at">Newest First</SelectItem>
                                    <SelectItem value="created_at_asc">Oldest First</SelectItem>
                                    <SelectItem value="products_count">Most Products</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Button @click="filterCategories" variant="outline" class="w-full gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.121A1 1 0 013 6.414V4z" />
                                </svg>
                                Filter
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Categories Data Table -->
            <Card>
                <CardHeader class="pb-4">
                    <div class="flex items-center justify-between">
                        <CardTitle class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Categories List
                        </CardTitle>
                        <Button @click="refreshCategories" variant="outline" size="sm" class="gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </Button>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div v-if="loading" class="flex items-center justify-center p-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    </div>

                    <div v-else-if="!categories.data || categories.data.length === 0" class="text-center p-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                        <p class="text-gray-600 mb-4">Start by creating your first category</p>
                        <Button @click="createCategory" class="gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Create Category
                        </Button>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Category</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Menu</TableHead>
                                    <TableHead>Products</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Sale Price</TableHead>
                                    <TableHead>Created</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in categories.data" :key="item.id" class="hover:bg-gray-50">
                                    <!-- Name Column -->
                                    <TableCell>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ item.name }}</div>
                                                <div v-if="item.type" class="text-sm text-gray-500">{{ item.type }}
                                                </div>
                                            </div>
                                        </div>
                                    </TableCell>

                                    <!-- Description Column -->
                                    <TableCell>
                                        <div class="max-w-48 text-sm text-gray-600 truncate">
                                            {{ item.shortDescription }}
                                        </div>
                                    </TableCell>

                                    <!-- Menu Column -->
                                    <TableCell>
                                        <Badge :variant="item.menu ? 'default' : 'secondary'" class="gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                            {{ item.menuName }}
                                        </Badge>
                                    </TableCell>

                                    <!-- Products Count Column -->
                                    <TableCell>
                                        <Badge :variant="item.products_count > 0 ? 'default' : 'secondary'"
                                            class="gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            {{ item.products_count }}
                                        </Badge>
                                    </TableCell>

                                    <!-- Status Column -->
                                    <TableCell>
                                        <Badge
                                            :variant="item.status === 'active' ? 'default' : item.status === 'draft' ? 'secondary' : 'destructive'"
                                            class="gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path v-if="item.status === 'active'" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                <path v-else-if="item.status === 'draft'" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ item.status }}
                                        </Badge>
                                    </TableCell>

                                    <!-- Price Sale Column -->
                                    <TableCell>
                                        <div v-if="item.price_sale && item.price_sale !== '0.00'"
                                            class="font-medium text-green-600">
                                            {{ item.formattedPriceSale }}
                                        </div>
                                        <span v-else class="text-gray-400">—</span>
                                    </TableCell>

                                    <!-- Created Date Column -->
                                    <TableCell>
                                        <div class="text-sm text-gray-600">
                                            {{ item.created_at_formatted }}
                                        </div>
                                    </TableCell>

                                    <!-- Actions Column -->
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <Button @click="viewCategory(item)" variant="ghost" size="sm"
                                                class="h-8 w-8 p-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </Button>
                                            <Button @click="editCategory(item)" variant="ghost" size="sm"
                                                class="h-8 w-8 p-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </Button>
                                            <Button @click="deleteCategory(item)" variant="ghost" size="sm"
                                                class="h-8 w-8 p-0 text-red-600 hover:text-red-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="categories.data && categories.data.length > 0" class="border-t bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Showing {{ categories.from || 0 }} to {{ categories.to || 0 }}
                                of {{ categories.total || 0 }} entries
                            </div>
                            <div v-if="categories.last_page > 1" class="flex items-center gap-2">
                                <Button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1"
                                    variant="outline" size="sm">
                                    Previous
                                </Button>
                                <div class="flex items-center gap-1">
                                    <Button v-for="page in visiblePages" :key="page" @click="changePage(page)"
                                        :variant="page === currentPage ? 'default' : 'outline'" size="sm" class="w-8">
                                        {{ page }}
                                    </Button>
                                </div>
                                <Button @click="changePage(currentPage + 1)"
                                    :disabled="currentPage >= categories.last_page" variant="outline" size="sm">
                                    Next
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Card from '@/Components/ui/card/Card.vue'

// Props
const props = defineProps({
    categories: Object,
    filters: Object
})

// Reactive data
const loading = ref(false)
const search = ref(props.filters?.search || '')
const selectedStatus = ref(props.filters?.status || '')
const selectedSort = ref(props.filters?.sort || 'created_at')
const currentPage = ref(1)

// Computed properties
const activeCategories = computed(() => {
    return props.categories?.data?.filter(category => category.status === 'active').length || 0
})

const draftCategories = computed(() => {
    return props.categories?.data?.filter(category => category.status === 'draft').length || 0
})

const totalProducts = computed(() => {
    return props.categories?.data?.reduce((total, category) => total + (category.products_count || 0), 0) || 0
})

const visiblePages = computed(() => {
    const totalPages = props.categories?.last_page || 1
    const current = currentPage.value
    const delta = 2
    const range = []

    for (let i = Math.max(2, current - delta); i <= Math.min(totalPages - 1, current + delta); i++) {
        range.push(i)
    }

    if (current - delta > 2) {
        range.unshift("...")
    }
    if (current + delta < totalPages - 1) {
        range.push("...")
    }

    range.unshift(1)
    if (totalPages > 1) {
        range.push(totalPages)
    }

    return range.filter((item, index, arr) => arr.indexOf(item) === index)
})

// Methods
const createCategory = () => {
    router.get(route('dashboard.categories.create'))
}

const viewCategory = (category) => {
    router.get(route('dashboard.categories.show', category.id))
}

const editCategory = (category) => {
    router.get(route('dashboard.categories.edit', category.id))
}

const deleteCategory = (category) => {
    router.get(route('dashboard.categories.delete', category.id))
}

const filterCategories = () => {
    loading.value = true

    const params = {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
        sort: selectedSort.value || undefined,
        page: 1
    }

    // Remove undefined values
    Object.keys(params).forEach(key => {
        if (params[key] === undefined) {
            delete params[key]
        }
    })

    router.get(route('dashboard.categories.index'), params, {
        preserveState: true,
        onFinish: () => {
            loading.value = false
        }
    })
}

const refreshCategories = () => {
    router.reload({ only: ['categories'] })
}

const changePage = (page) => {
    if (page === "..." || page < 1 || page > (props.categories?.last_page || 1)) return

    loading.value = true
    currentPage.value = page

    const params = {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
        sort: selectedSort.value || undefined,
        page: page
    }

    // Remove undefined values
    Object.keys(params).forEach(key => {
        if (params[key] === undefined) {
            delete params[key]
        }
    })

    router.get(route('dashboard.categories.index'), params, {
        preserveState: true,
        onFinish: () => {
            loading.value = false
        }
    })
}

onMounted(() => {
    currentPage.value = props.categories?.current_page || 1
})
</script>