function dropdownPts() {
    return {
        Pt: "",
        selectedPtIndex: "",
        Pts: dbPts,

        get filteredPts() {
            if (!this.Pt || this.Pt === "") {
                return [];
            }

            return this.Pts.filter(
                (Pt) =>
                    Pt &&
                    Pt.nama &&
                    Pt.nama.toLowerCase().includes(this.Pt.toLowerCase())
            );
        },

        reset() {
            this.Pt = "";
        },

        selectNextPt() {
            if (this.selectedPtIndex === "") {
                this.selectedPtIndex = 0;
            } else {
                this.selectedPtIndex++;
            }

            if (this.selectedPtIndex === this.filteredPts.length) {
                this.selectedPtIndex = 0;
            }

            this.focusSelectedPt();
        },

        selectPreviousPt() {
            if (this.selectedPtIndex === "") {
                this.selectedPtIndex = this.filteredPts.length - 1;
            } else {
                this.selectedPtIndex--;
            }

            if (this.selectedPtIndex < 0) {
                this.selectedPtIndex = this.filteredPts.length - 1;
            }

            this.focusSelectedPt();
        },

        focusSelectedPt() {
            this.$refs.Pts.children[this.selectedPtIndex + 1].scrollIntoView({
                block: "nearest",
            });
        },

        setPtValuePt(Pt) {
            let currentPt = Pt ? Pt : this.filteredPts[this.selectedPtIndex];
            this.Pt = Pt.nama;
        },
    };
}

function isiText() {
    return {
        inputValue1: "",
        setInputFromButton() {
            // Mendapatkan teks dari tombol
            //let buttonText = document.querySelector("button").innerText;
            // Mengatur nilai input menjadi teks dari tombol
            this.inputValue1 = "aaaa";
        },
    };
}
