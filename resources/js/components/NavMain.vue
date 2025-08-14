<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ChevronDown } from 'lucide-vue-next';

const props = defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const expandedMenus = ref<Set<string>>(new Set());

// Fixed to handle both toggling and navigation
const handleSubmenuItemClick = (href: string, parentTitle: string) => {
    // Close the submenu
    expandedMenus.value.delete(parentTitle);
    // Navigate to the page
    window.location.href = href;
};

const toggleSubmenu = (title: string, event: Event) => {
    event.preventDefault();
    event.stopPropagation();
    if (expandedMenus.value.has(title)) {
        expandedMenus.value.delete(title);
    } else {
        expandedMenus.value.add(title);
    }
};

const isActive = (item: NavItem) => {
    if (item.href) {
        return page.url.startsWith(item.href);
    }
    if (item.submenuItems) {
        return item.submenuItems.some(subitem => page.url.startsWith(subitem.href));
    }
    return false;
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <template v-if="item.hasSubmenu">
                    <SidebarMenuButton
                        :is-active="isActive(item)"
                        :tooltip="item.title"
                        @click="(e) => toggleSubmenu(item.title, e)"
                        class="w-full"
                    >
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-2">
                                <component :is="item.icon" class="h-4 w-4" />
                                <span>{{ item.title }}</span>
                            </div>
                            <ChevronDown
                                class="h-4 w-4 transition-transform"
                                :class="{ 'rotate-180': expandedMenus.has(item.title) }"
                            />
                        </div>
                    </SidebarMenuButton>
                    <div v-show="expandedMenus.has(item.title)"
                         class="pl-8 py-1 space-y-1">
                        <button
                            v-for="subItem in item.submenuItems"
                            :key="subItem.title"
                            class="w-full text-left px-3 py-2 text-sm rounded-md transition-colors"
                            :class="[
                                'text-gray-200 hover:bg-white hover:text-black',
                                { 'bg-white !text-black': page.url.startsWith(subItem.href) }
                            ]"
                            @click="handleSubmenuItemClick(subItem.href, item.title)"
                        >
                            {{ subItem.title }}
                        </button>
                    </div>
                </template>
                <SidebarMenuButton v-else as-child :is-active="isActive(item)" :tooltip="item.title">
                    <Link :href="item.href" class="flex items-center gap-2">
                        <component :is="item.icon" class="h-4 w-4" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>

<style scoped>
.submenu-enter-active,
.submenu-leave-active {
    transition: all 0.3s ease;
}
.submenu-enter-from,
.submenu-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
