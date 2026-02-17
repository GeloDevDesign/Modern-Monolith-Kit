<script setup>
import { Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from "primevue/confirmdialog";
import { ref } from "vue";

const props = defineProps({
    hasEdit: {
        type: Boolean,
        default: true,
    },
    hasDelete: {
        type: Boolean,
        default: true,
    },
    hasView: {
        type: Boolean,
        default: false,
    },
    editUrl: {
        type: String,
        default: null,
    },
    deleteUrl: {
        type: String,
        default: null,
    },
    viewUrl: {
        type: String,
        default: null,
    },
    restoreUrl: {
        type: String,
        default: null,
    },
    forceDeleteUrl: {
        type: String,
        default: null,
    },
    deletedAt: {
        type: String,
        default: null,
    },
});

const confirm = useConfirm();
const group = `entity-actions-${Math.random().toString(36).slice(2, 11)}`;

const isProcessing = ref(false);
const actionType = ref('delete'); // 'delete', 'restore', 'forceDelete'

const handleAction = (acceptCallback) => {
    isProcessing.value = true;
    
    let url = props.deleteUrl;
    let method = 'delete';

    if (actionType.value === 'restore') {
        url = props.restoreUrl;
        method = 'put';
    } else if (actionType.value === 'forceDelete') {
        url = props.forceDeleteUrl;
        method = 'delete';
    }

    router.visit(url, {
        method: method,
        preserveScroll: true,
        onSuccess: () => {
            isProcessing.value = false;
            acceptCallback();
        },
        onError: () => {
            isProcessing.value = false;
        },
        onFinish: () => {
            if (!isProcessing.value) {
            }
        },
    });
};

const confirmDelete = () => {
    actionType.value = 'delete';
    confirm.require({
        group: group,
        message: "Are you sure you want to delete this record?",
        header: "Confirm Delete",
        icon: "pi pi-exclamation-triangle",
    });
};

const confirmRestore = () => {
    actionType.value = 'restore';
    confirm.require({
        group: group,
        message: "Are you sure you want to restore this record?",
        header: "Confirm Restore",
        icon: "pi pi-refresh",
    });
};

const confirmForceDelete = () => {
    actionType.value = 'forceDelete';
    confirm.require({
        group: group,
        message: "Are you sure you want to permanently delete this record? This action cannot be undone.",
        header: "Confirm Permanent Delete",
        icon: "pi pi-exclamation-triangle",
    });
};
</script>

<template>
    <div class="flex gap-2">
       
        <template v-if="!deletedAt">
            <Link v-if="hasEdit && editUrl" :href="editUrl">
                <Button
                    icon="pi pi-pencil"
                    outlined
                    rounded
                    severity="info"
                    size="small"
                    v-tooltip.top="'Edit'"
                />
            </Link>

            <Link v-if="hasView && viewUrl" :href="viewUrl">
                <Button
                    icon="pi pi-eye"
                    outlined
                    rounded
                    severity="success"
                    size="small"
                    v-tooltip.top="'View'"
                />
            </Link>

            <Button
                v-if="hasDelete && deleteUrl"
                icon="pi pi-trash"
                outlined
                rounded
                severity="danger"
                size="small"
                v-tooltip.top="'Delete'"
                @click="confirmDelete"
            />
        </template>
        
        <template v-else>
             <Button
                v-if="restoreUrl"
                icon="pi pi-refresh"
                outlined
                rounded
                severity="success"
                size="small"
                v-tooltip.top="'Restore'"
                @click="confirmRestore"
            />
            <Button
                v-if="forceDeleteUrl"
                icon="pi pi-trash"
                outlined
                rounded
                severity="danger"
                size="small"
                v-tooltip.top="'Force Delete'"
                @click="confirmForceDelete"
            />
        </template>

        <ConfirmDialog :group="group" :draggable="false">
            <template #container="{ message, acceptCallback, rejectCallback }">
                <div
                    class="flex flex-col gap-5 p-6 surface-card rounded-xl shadow-xl max-w-md"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                            :class="actionType === 'restore' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30'"
                        >
                            <i
                                :class="[message.icon, actionType === 'restore' ? 'text-green-500' : 'text-red-500']"
                                class="text-xl"
                            ></i>
                        </div>
                        <span class="font-bold text-lg block">{{
                            message.header
                        }}</span>
                    </div>

                    <p
                        class="text-surface-600 dark:text-surface-300 leading-relaxed"
                    >
                        {{ message.message }}
                    </p>

                    <div class="flex justify-end gap-3 mt-2">
                        <Button
                            label="Cancel"
                            severity="secondary"
                            outlined
                            @click="rejectCallback"
                            :disabled="isProcessing"
                            size="small"
                        ></Button>
                        <Button
                            :label="isProcessing ? 'Processing...' : (actionType === 'restore' ? 'Restore' : 'Delete')"
                            :severity="actionType === 'restore' ? 'success' : 'danger'"
                            size="small"
                            @click="handleAction(acceptCallback)"
                            :loading="isProcessing"
                        ></Button>
                    </div>
                </div>
            </template>
        </ConfirmDialog>
    </div>
</template>
