<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';

interface WidgetTypeDefinition {
    label: string;
    icon: string;
}

const props = defineProps<{
    widgetTypes: Record<string, WidgetTypeDefinition>;
}>();

const emit = defineEmits<{
    close: [];
    added: [];
}>();

const selected = ref<string>('');

const form = useForm({ type: '' });

const submit = () => {
    form.type = selected.value;
    form.post(route('settings.dashboard.widgets.store'), {
        preserveScroll: true,
        onSuccess: () => emit('added'),
    });
};
</script>

<template>
    <!-- Backdrop -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @click.self="emit('close')"
    >
        <div class="w-full max-w-md rounded-lg bg-background p-6 shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Add Widget</h2>

            <div class="space-y-2 mb-6">
                <Label>Select a widget type</Label>
                <div class="grid grid-cols-1 gap-2">
                    <button
                        v-for="(def, typeKey) in widgetTypes"
                        :key="typeKey"
                        type="button"
                        class="flex items-center gap-3 rounded-md border p-3 text-left transition hover:bg-accent"
                        :class="selected === typeKey ? 'border-primary bg-accent' : 'border-border'"
                        @click="selected = typeKey"
                    >
                        <span class="font-medium text-sm">{{ def.label }}</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <Button variant="ghost" @click="emit('close')">Cancel</Button>
                <Button
                    :disabled="!selected || form.processing"
                    @click="submit"
                >
                    Add
                </Button>
            </div>
        </div>
    </div>
</template>