import preset from "./vendor/filament/support/tailwind.config.preset";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
    plugins: [forms, typography],
};
