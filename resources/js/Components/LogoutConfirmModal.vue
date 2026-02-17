<script setup>
import ConfirmDialog from "primevue/confirmdialog";
import Button from "primevue/button";

defineProps({
    group: {
        type: String,
        default: "confirm-dialog",
    },
});
</script>

<template>
    <ConfirmDialog :group="group" :draggable="false" :style="{ width: '24rem' }">
        <template #container="{ message, acceptCallback, rejectCallback }">
            <div
                class="flex flex-col items-center gap-5 p-6 surface-card rounded-xl shadow-xl w-full text-center"
            >
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0"
                    >
                        <i
                            :class="message.icon"
                            class="text-red-500 text-2xl"
                        ></i>
                    </div>
                    <span class="font-bold text-xl block">{{
                        message.header
                    }}</span>
                </div>

                <p
                    class="text-surface-600 dark:text-surface-300 leading-relaxed"
                >
                    {{ message.message }}
                </p>

                <div class="flex justify-center gap-3 mt-2">
                    <Button
                        label="Cancel"
                        severity="secondary"
                        outlined
                        @click="rejectCallback"
                        size="small"
                    ></Button>
                    <Button
                        label="Logout"
                        severity="danger"
                        size="small"
                        @click="acceptCallback"
                    ></Button>
                </div>
            </div>
        </template>
    </ConfirmDialog>
</template>
