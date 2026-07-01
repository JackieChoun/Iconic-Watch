<template>
    <footer class="border-t border-black py-4">
        <div class="flex flex-col items-center justify-evenly md:flex-row">
            <a href="/ML" class="underline">Mentions légales</a>
            <a href="mailto:ydv311@hotmail.fr" target="_blank" title="Mon mail" class="py-2 underline md:py-0">Contact</a>
            <a href="#haut" class="underline">Haut de page</a>

            <!-- Switch Dark Mode -->
            <!-- <label for="darkMode" class="hidden h-[34px] w-[60px] cursor-pointer md:relative md:inline-block">
                <input type="checkbox" id="darkMode" name="darkMode" class="peer h-0 w-0 opacity-0" />
                <span class="absolute inset-0 rounded-full bg-gray-300 transition-colors duration-400 peer-checked:bg-neutral-800"></span>
                <span
                    class="absolute bottom-1 left-1 h-[26px] w-[26px] rounded-full bg-[#fdfdfd] text-center text-sm leading-[26px] transition-transform duration-400 peer-checked:translate-x-6"
                >
                    🌓
                </span>
            </label> -->
            <div class="flex flex-col items-center">
                <img src="favicon.svg" alt="logo" class="h-auto w-[40px]" />
                <p class="text-xs text-gray-500">© {{ getCopyrightYear() }} ICONIC WATCH. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</template>
<script>
export default {
    methods: {
        getCopyrightYear() {
            const currentYear = new Date().getFullYear();
            return `${currentYear}`;
        },
    },
};
</script>
