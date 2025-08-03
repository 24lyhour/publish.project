<script setup>
import { ChevronRight, Edit, Eye, Trash2, Package, Calendar, DollarSign, Tag, User, ImageIcon } from 'lucide-vue-next';
import Card from '@/Components/ui/card/Card.vue';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardDescription from '@/Components/ui/card/CardDescription.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import CardTitle from '@/Components/ui/card/CardTitle.vue';
import Badge from '@/Components/ui/badge/Badge.vue';
import Button from '@/Components/ui/button/Button.vue';

const props = defineProps({
    category: Object,
    filters: Object
})

const getActionIcon = (actionName) => {
    const icons = {
        view: Eye,
        edit: Edit,
        delete: Trash2
    };
    return icons[actionName] || Eye;
};
</script>

<template>

    <div class="min-h-screen bg-gray-50/50 p-4 lg:p-8">
        <!-- Breadcrumb Header -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-muted-foreground mb-4">
                <span>Dashboard</span>
                <ChevronRight class="h-4 w-4" />
                <span>Categories</span>
                <ChevronRight class="h-4 w-4" />
                <span class="text-foreground font-medium">{{ category.displayName }}</span>
            </nav>

            <!-- Page Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold tracking-tight">{{ category.displayName }}</h1>
                    <p class="text-muted-foreground" v-if="category.shortDescription">
                        {{ category.shortDescription }}
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="flex items-center gap-3">
                    <div class="flex gap-2">
                        <Badge v-for="badge in category.badges" :key="badge.text" :variant="badge.color"
                            class="px-3 py-1">
                            {{ badge.text }}
                        </Badge>
                    </div>
                    <div class="flex gap-2">
                        <Button v-for="action in category.actions" :key="action.name"
                            :variant="action.color === 'primary' ? 'default' : action.color === 'warning' ? 'secondary' : 'destructive'"
                            class="min-w-[100px]">
                            <component :is="getActionIcon(action.name)" class="w-4 h-4 mr-2" />
                            {{ action.label }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overview Card -->
                <Card class="border-0 shadow-lg">
                    <CardHeader class="pb-3">
                        <CardTitle class="flex items-center gap-2">
                            <Tag class="h-5 w-5 text-primary" />
                            Category Overview
                        </CardTitle>
                        <CardDescription>Essential information about this category</CardDescription>
                    </CardHeader>
                    <CardContent class="pt-0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-muted-foreground">Category ID</span>
                                    <span class="font-mono text-sm">#{{ category.id }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-muted-foreground">Name</span>
                                    <span class="font-semibold">{{ category.name }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-muted-foreground">Type</span>
                                    <Badge variant="outline">{{ category.type }}</Badge>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                    <span class="text-sm font-medium text-muted-foreground">Status</span>
                                    <Badge :variant="category.statusColor">
                                        {{ category.status }}
                                    </Badge>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50"
                                    v-if="category.menu">
                                    <span class="text-sm font-medium text-muted-foreground">Menu</span>
                                    <span class="font-semibold">{{ category.menu.name }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-lg bg-green-50"
                                    v-if="category.price_sale">
                                    <span class="text-sm font-medium text-green-700">Sale Price</span>
                                    <span class="font-bold text-green-600 text-lg">{{ category.formattedPriceSale
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Description Card -->
                <Card class="border-0 shadow-lg" v-if="category.description">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <User class="h-5 w-5 text-primary" />
                            Description
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-muted-foreground leading-relaxed">{{ category.description }}</p>
                    </CardContent>
                </Card>

                <!-- Image Gallery -->
                <Card class="border-0 shadow-lg" v-if="category.imageUrl">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <ImageIcon class="h-5 w-5 text-primary" />
                            Category Image
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="relative group">
                            <img :src="category.imageUrl" :alt="category.name"
                                class="w-full h-64 object-cover rounded-xl border border-gray-200 shadow-sm group-hover:shadow-md transition-shadow duration-300" />
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/5 rounded-xl transition-colors duration-300">
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Right Column - Stats & Meta -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <Card class="border-0 shadow-lg">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Package class="h-5 w-5 text-primary" />
                            Statistics
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="text-center p-6 rounded-xl bg-blue-50 border border-blue-100">
                            <div class="text-3xl font-bold text-blue-600 mb-1">{{ category.products_count }}</div>
                            <div class="text-sm text-blue-600 font-medium">Products</div>
                        </div>

                        <div class="text-center p-6 rounded-xl bg-green-50 border border-green-100"
                            v-if="category.price_sale">
                            <div class="text-2xl font-bold text-green-600 mb-1">{{ category.formattedPriceSale }}
                            </div>
                            <div class="text-sm text-green-600 font-medium">Sale Price</div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Timeline Card -->
                <Card class="border-0 shadow-lg">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Calendar class="h-5 w-5 text-primary" />
                            Timeline
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <div class="flex-1">
                                <div class="text-sm font-medium">Created</div>
                                <div class="text-xs text-muted-foreground">{{ category.created_at_formatted }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <div class="flex-1">
                                <div class="text-sm font-medium">Last Updated</div>
                                <div class="text-xs text-muted-foreground">{{ category.updated_at_formatted }}</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Menu Details -->
                <Card class="border-0 shadow-lg" v-if="category.menu">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <DollarSign class="h-5 w-5 text-primary" />
                            Menu Details
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Menu Name</span>
                                <span class="font-semibold">{{ category.menu.name }}</span>
                            </div>
                            <div class="flex justify-between items-center" v-if="category.menu.description">
                                <span class="text-sm text-muted-foreground">Description</span>
                                <span class="text-sm">{{ category.menu.description }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>

</template>