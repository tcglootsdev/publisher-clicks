(() => {
    if (window.PtImageInput !== undefined) return;
    window.PtImageInput = function () {
        const partId = 'pt-image-input';
        const getInstance = (assignedId) => {
            const realId = '.' + partId + '.' + assignedId;

            let ktImageInput = null;

            const setImage = (imageUrl) => {
                $(realId).css('background-image', 'url("' + imageUrl + '")')
            }

            const getImage = () => {
                const imageInput = ktImageInput.getInputElement();
                return imageInput.files[0];
            }

            const reset = () => {
                ktImageInput.cancelElement.click();
            }

            return {
                init: () => {
                    ktImageInput = KTImageInput.getInstance(document.querySelector(realId));
                },
                setImage,
                getImage,
                reset,
            }
        }
        return {
            getInstance
        }
    }();
})();
