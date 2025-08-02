var Chrome = VueColor.Chrome;

Vue.component("colorpicker", {
    components: {
        "chrome-picker": Chrome
    },
    template: `
<div class="input-group color-picker gap-10" ref="colorpicker">
    <input type="text" class="input" v-model="colorValue" @focus="showPicker()" @input="updateFromInput" />
    <span class="input-group-addon color-picker-container">
        <span class="current-color" :style="'background-color: ' + currentColor" @click="togglePicker()"></span>
        <chrome-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" />
    </span>
</div>`,
    props: ["value"],  // Use 'value' for default color
    data() {
        return {
            colors: { hex: "#000000" },
            colorValue: "",
            displayPicker: false
        };
    },
    computed: {
        currentColor() {
            return this.colors.hex || this.colorValue;  // Display the hex or RGB
        }
    },
    mounted() {
        this.setColor(this.value);
    },
    methods: {
        setColor(color) {
            this.updateColors(color);
            this.colorValue = color;
        },
        updateColors(color) {
            if (color.startsWith("#")) {
                this.colors = { hex: color };
            } else if (color.includes(",")) {
                const rgb = color.split(',').map(c => parseInt(c.trim(), 10));
                const hex = `#${((1 << 24) + (rgb[0] << 16) + (rgb[1] << 8) + rgb[2]).toString(16).slice(1)}`;
                this.colors = { hex: hex };
            } else {
                this.colors = { hex: color }; // Fallback for other formats
            }
        },
        showPicker() {
            document.addEventListener("click", this.documentClick);
            this.displayPicker = true;
        },
        hidePicker() {
            document.removeEventListener("click", this.documentClick);
            this.displayPicker = false;
        },
        togglePicker() {
            this.displayPicker ? this.hidePicker() : this.showPicker();
        },
        updateFromInput() {
            this.updateColors(this.colorValue);
        },
        updateFromPicker(color) {
            this.colors = color;
            this.colorValue = color.hex;  // Update input with hex value
            this.$emit("input", this.colorValue);
        },
        documentClick(e) {
            var el = this.$refs.colorpicker,
                target = e.target;
            if (el !== target && !el.contains(target)) {
                this.hidePicker();
            }
        }
    },
    watch: {
        value(val) {
            this.setColor(val);
        }
    }
});

// Initialize Vue for the active color picker
new Vue({
    el: ".color-picker-active"
});
