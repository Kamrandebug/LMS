<x-layouts.app>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 pt-8">
        <livewire:mock-exam-engine :mockExam="$mockExam" :key="'mek-' . $mockExam->id" />
    </div>
</x-layouts.app>
