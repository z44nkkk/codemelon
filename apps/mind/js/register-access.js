
async function registerAccess(){
    const data = {
        op: "registerAccess",
        page: "mind",
        device: detectDevice()
    }
    const url = `../../back-end/controllers/users.controller.php`;
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
        });
        const result = await response.json();

        if (result) {
            return result.success;
        } 
    } catch (error) {
        message("Error: " + error.message, "error");
    }

}

function detectDevice() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;


    if (/iPhone|iPad|iPod/i.test(userAgent)) {
        return "iOS (iPhone/iPad)";
    }


    if (/android/i.test(userAgent)) {
        return "Android";
    }


    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }


    if (/tablet|ipad|playbook|silk/i.test(userAgent)) {
        return "Tablet";
    }


    if (/mobile|iphone|ipod|android|blackberry|opera mini|windows phone/i.test(userAgent)) {
        return "Mobile";
    }


    return "Desktop";
}


