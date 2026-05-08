<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title" class="flex flex-col gap-3">
                <SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
                    <component
                        :is="item.external ? 'a' : Link"
                        :href="item.href"
                        :target="item.external ? '_blank' : undefined"
                        :rel="item.external ? 'noopener noreferrer' : undefined"
                    >
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                        <span v-if="item.external" class="text-xs opacity-60">↗</span>
                    </component>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
