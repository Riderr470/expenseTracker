<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

// ── Types ────────────────────────────────────────────────────
interface ConfigField {
    type: 'text' | 'select';
    label: string;
    required: boolean;
    default: string;
    options?: string[];
}

interface WidgetTypeDefinition {
    label: string;
    icon: string;
    config_schema: Record<string, ConfigField>;
}

interface Widget {
    id: number;
    key: string;
    type: string;
    config: Record<string, string>;
    position: number;
}

const props = defineProps<{
    widget: Widget;
    schema: WidgetTypeDefinition;
    isFirst: boolean;
    isLast: boolean;
}>();

const emit = defineEmits<{
    move: [direction: 'up' | 'down'];
}>();

// ── Edit State ───────────────────────────────────────────────
const isEditing = ref(false);

const form = useForm({
    config: { ...props.widget.config },
});

const submit = () => {
    form.patch(route('settings.dashboard.widgets.update', props.widget.id), {
        preserveScroll: true,
        onSuccess: () => { isEditing.value = false; },
    });
};

const cancel = () => {
    form.reset();
    isEditing.value = false;
};

// ── Delete ───────────────────────────────────────────────────
const deleteForm = useForm({});
const remove = () => {
    deleteForm.delete(route('settings.dashboard.widgets.destroy', props.widget.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="rounded-lg border bg-card p-4 shadow-sm">

        <!-- Header row -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Move buttons -->
                <div class="flex flex-col gap-0.5">
                    <button
                        :disabled="isFirst"
                        class="text-muted-foreground hover:text-foreground disabled:opacity-30"
                        @click="emit('move', 'up')"
                        aria-label="Move up"
                    >
                        ↑
                    </button>
                    <button
                        :disabled="isLast"
                        class="text-muted-foreground hover:text-foreground disabled:opacity-30"
                        @click="emit('move', 'down')"
                        aria-label="Move down"
                    >
                        ↓
                    </button>
                </div>

                <div>
                    <p class="font-medium text-sm">{{ schema.label }}</p>
                    <!-- Show current title from config -->
                    <p class="text-xs text-muted-foreground">{{ widget.config.title }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    @click="isEditing = !isEditing"
                >
                    {{ isEditing ? 'Cancel' : 'Edit' }}
                </Button>
                <Button
                    variant="destructive"
                    size="sm"
                    :disabled="deleteForm.processing"
                    @click="remove"
                >
                    Remove
                </Button>
            </div>
        </div>

        <!-- Inline edit form — driven entirely by schema -->
        <form
            v-if="isEditing"
            @submit.prevent="submit"
            class="mt-4 space-y-4 border-t pt-4"
        >
            <template v-for="(fieldDef, fieldKey) in schema.config_schema" :key="fieldKey">

                <!-- Text field -->
                <div v-if="fieldDef.type === 'text'" class="grid gap-2">
                    <Label :for="`${widget.key}-${fieldKey}`">{{ fieldDef.label }}</Label>
                    <Input
                        :id="`${widget.key}-${fieldKey}`"
                        v-model="form.config[fieldKey]"
                        :required="fieldDef.required"
                        :placeholder="fieldDef.default"
                    />
                    <InputError :message="form.errors[`config.${fieldKey}`]" />
                </div>

                <!-- Select field -->
                <div v-else-if="fieldDef.type === 'select'" class="grid gap-2">
                    <Label :for="`${widget.key}-${fieldKey}`">{{ fieldDef.label }}</Label>
                    <Select v-model="form.config[fieldKey]">
                        <SelectTrigger :id="`${widget.key}-${fieldKey}`">
                            <SelectValue :placeholder="`Select ${fieldDef.label}`" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="option in fieldDef.options"
                                :key="option"
                                :value="option"
                            >
                                {{ option }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors[`config.${fieldKey}`]" />
                </div>

            </template>

            <div class="flex items-center gap-4">
                <Button type="submit" :disabled="form.processing">Save</Button>
                <Button type="button" variant="ghost" @click="cancel">Cancel</Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                </Transition>
            </div>
        </form>

    </div>
</template>