<script setup>
import { ref, computed } from "vue";
import { useToast } from "primevue/usetoast";

const visible = ref(false);
const toast = useToast();

const notifications = ref([
    {
        id: 1,
        type: 'message',
        title: "New Message",
        message: "You have received a new message from John.",
        time: "2 min ago",
        read: false,
    },
    {
        id: 2,
        type: 'system',
        title: "System Update",
        message: "System maintenance scheduled for tonight.",
        time: "1 hour ago",
        read: true,
    },
    {
        id: 3,
        type: 'task',
        title: "Task Completed",
        message: "Project X task has been marked as completed.",
        time: "3 hours ago",
        read: true,
    },
    {
        id: 4,
        type: 'alert',
        title: "Storage Warning",
        message: "Your storage is almost full (95%).",
        time: "5 hours ago",
        read: false,
    },
]);

const unreadCount = computed(() => notifications.value.filter(n => !n.read).length);

const markAllAsRead = () => {
    notifications.value.forEach(n => n.read = true);
    toast.add({ severity: 'success', summary: 'Success', detail: 'All notifications marked as read', life: 3000 });
};

const markAsRead = (notification) => {
    if (!notification.read) {
        notification.read = true;
    }
};

const getIcon = (type) => {
    switch (type) {
        case 'message': return 'pi pi-envelope';
        case 'system': return 'pi pi-cog';
        case 'task': return 'pi pi-check-circle';
        case 'alert': return 'pi pi-exclamation-circle';
        default: return 'pi pi-bell';
    }
};

const getIconColor = (type) => {
    switch (type) {
        case 'message': return 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400';
        case 'system': return 'bg-gray-100 text-gray-600 dark:bg-gray-500/20 dark:text-gray-400';
        case 'task': return 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400';
        case 'alert': return 'bg-orange-100 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400';
        default: return 'bg-primary-100 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400';
    }
};
</script>

<template>
    <div class="relative">
        <OverlayBadge
            :value="unreadCount > 0 ? unreadCount.toString() : null"
            severity="danger"
            class="cursor-pointer"
            @click="visible = true"
            :pt="{
                pcBadge: {
                    root: {
                        class: '!min-w-[1rem] !h-[0.85rem] !text-[0.55rem] !leading-[0.85rem] !p-0 flex items-center justify-center',
                    },
                },
            }"
        >
            <i class="pi pi-bell text-xl text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors" />
        </OverlayBadge>

        <Drawer
            v-model:visible="visible"
            position="right"
            class="!w-80 sm:!w-[400px]"
            :pt="{
                header: { class: '!p-4 !border-b !border-gray-100 dark:!border-zinc-800' },
                content: { class: '!p-0' }
            }"
        >
            <template #header>
                <div class="flex items-center justify-between w-full">
                    <span class="font-bold text-lg text-gray-800 dark:text-gray-100">Notifications</span>
                    <button 
                        v-if="unreadCount > 0"
                        @click="markAllAsRead"
                        class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors"
                    >
                        Mark all as read
                    </button>
                </div>
            </template>

            <div class="flex flex-col h-full overflow-y-auto custom-scrollbar">
                <div v-if="notifications.length > 0" class="flex flex-col">
                    <div 
                        v-for="notification in notifications" 
                        :key="notification.id"
                        @click="markAsRead(notification)"
                        class="flex items-start gap-4 p-4 border-b border-gray-100 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer relative group"
                        :class="{ 'bg-primary-50/50 dark:bg-primary-900/10': !notification.read }"
                    >
                        <!-- Icon -->
                        <div class="shrink-0 rounded-full w-10 h-10 flex items-center justify-center" :class="getIconColor(notification.type)">
                            <i :class="getIcon(notification.type)" class="text-lg"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate pr-2" :class="{ 'text-primary-700 dark:text-primary-400': !notification.read }">
                                    {{ notification.title }}
                                </h4>
                                <span class="text-[10px] text-gray-400 shrink-0 whitespace-nowrap mr-6">{{ notification.time }}</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                {{ notification.message }}
                            </p>
                        </div>

                        <!-- Unread Indicator -->
                        <div v-if="!notification.read" class="absolute top-1/2 right-2 -translate-y-1/2 w-2 h-2 rounded-full bg-primary-500"></div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center h-64 text-center p-6">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                        <i class="pi pi-bell-slash text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-gray-900 dark:text-gray-100 font-medium mb-1">No notifications</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">You're all caught up! Check back later.</p>
                </div>
            </div>
        </Drawer>
    </div>
</template>
