<script setup lang="ts">

import { Head, Link, usePage } from '@inertiajs/vue3';
  import LoggerPatronSearch from '@/components/LoggerPatronSearch.vue';
import { AlertCircle, CircleCheckBig, X } from 'lucide-vue-next';
  import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
  import { onMounted, ref } from 'vue';

  defineProps({
      patron: Object,
      purposes: Object,
      search_term: String,
      search_button: Boolean,
      is_logout: Boolean,
  });

  // for alert
  const page = usePage()
  const showAlert = ref(true)
  onMounted(() => {
      if (page.props.flash.error) {
          setTimeout(() => {
              showAlert.value = false
          }, 5000)
      }
  })

  interface Flash {
      success?: string | null;
      error?: string | null;
  }

  declare module '@inertiajs/core' {
      interface PageProps {
          flash: Flash;
      }
  }

</script>

<template>
    <Head title="Patron Logger"></Head>
    <div class="flex min-h-screen flex-col items-center text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">
        <Link :href="route('home')" class="fixed top-0 left-0 opacity-0 bg-red-500">hi</Link>
        <Alert class="absolute top-5 right-5 w-fit pr-8" variant="destructive" v-if="page.props.flash.error && showAlert">
            <AlertCircle class="w-4 h-4" />
            <button @click="showAlert = false" class="absolute top-2 right-2 p-1 hover:bg-red-100 rounded-full transition-colors">
                <X class="w-4 h-4" />
            </button>
            <AlertTitle>Error</AlertTitle>
            <AlertDescription>
                {{ page.props.flash.error }}
            </AlertDescription>
        </Alert>
        <Alert class="fixed border-2 border-green-500 top-5 right-5 w-fit max-w-md pr-8 z-30" v-if="page.props.flash.success && showAlert">
            <CircleCheckBig />
            <button @click="showAlert = false" class="absolute top-2 right-2 p-1 hover:bg-red-100 rounded-full transition-colors">
                <X class="w-4 h-4" />
            </button>
            <AlertTitle>Success</AlertTitle>
            <AlertDescription>
                {{ page.props.flash.success }}
            </AlertDescription>
        </Alert>

        <div class="grid w-full opacity-100 transition-opacity duration-750 starting:opacity-0">
            <div class="p-8 min-w-full flex flex-col items-center">
                <LoggerPatronSearch :purposes="purposes" :patron="patron" :search_term="search_term"
                               :search_button="search_button" :is_logout="is_logout"
                />
            </div>
        </div>
    </div>
</template>
