<div class="flex h-screen overflow-y-auto w-60 px-4 pt-20">
    <nav class="flex flex-col w-full space-y-9" id="sidenavAccordion">
        <div class="flex flex-col space-y-6">
            <span class="text-xs uppercase font-semibold">Core</span>
            <a class="flex flex-row gap-2 text-blue-500" href="/dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
        </div>
        <div class="flex flex-col space-y-6">
            <span class="text-xs uppercase font-semibold">Interface</span>
            <a href="#" id="collapseTrigger" class="flex flex-row gap-2 text-blue-500">
                <div class="flex gap-2 items-center">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Pages
                </div>
                <div id="collapseArrow" class="transition-all duration-300 -rotate-90"><i class="fas fa-angle-down"></i>
                </div>
            </a>

            <div id="collapseLayouts"
                class="flex flex-col gap-5 max-h-0 transition-[max-height] duration-300 ease-in-out overflow-hidden text-blue-500 px-6">
                <a class="nav-link" href="/categories">Category</a>
                <a class="nav-link" href="/products">Product</a>
            </div>

        </div>
    </nav>
</div>


<script>
    const trigger = document.getElementById('collapseTrigger');
    const content = document.getElementById('collapseLayouts');
    const arrow = document.getElementById('collapseArrow');

    let isOpen = false;

    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        isOpen = !isOpen

        if (isOpen) {
            arrow.classList.remove('-rotate-90');
            content.style.maxHeight = content.scrollHeight + "px";
        } else {
            arrow.classList.add('-rotate-90');
            content.style.maxHeight = "0px";
        }
    });
</script>
