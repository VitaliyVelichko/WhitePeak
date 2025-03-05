// Скрипт для бургер-меню
document.addEventListener("DOMContentLoaded", () => {
    const burgerMenu = document.getElementById("burgerMenu");
    const navMenu = document.getElementById("navMenu");
  
    // Відкриття/закриття меню
    burgerMenu.addEventListener("click", () => {
      navMenu.classList.toggle("open");
    });
  
    // Закриття меню при натисканні на будь-яке місце
    document.addEventListener("click", (event) => {
      if (!burgerMenu.contains(event.target) && !navMenu.contains(event.target)) {
        navMenu.classList.remove("open");
      }
    });
  });

  
  /*                     CONTACTS FORM                    */ 
   function openForm() {
    document.getElementById("form-container").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function closeForm() {
    document.getElementById("form-container").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
/*                     ФОРМА для кнопки Залишити запит                   */

document.addEventListener("DOMContentLoaded", () => {
    const requestButton = document.querySelector(".request-button");
    const overlay = document.getElementById("overlay");
    const formContainer = document.getElementById("form-container");

    // Відкриття форми
    requestButton.addEventListener("click", () => {
        formContainer.style.display = "block";
        overlay.style.display = "block";
    });

    // Закриття форми при кліку на overlay
    overlay.addEventListener("click", () => {
        formContainer.style.display = "none";
        overlay.style.display = "none";
    });
});



 /*                                                  INFO                               */

document.addEventListener("DOMContentLoaded", () => {
    const stepBlocks = document.querySelectorAll('.step'); // Отримуємо всі блоки

    const observerOptions = {
        root: null, // Вікно перегляду
        threshold: 0.1, // 10% видимості для спрацювання
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const steps = Array.from(stepBlocks);
                steps.forEach((step, index) => {
                    // Встановлюємо змінну CSS для затримки
                    step.style.setProperty('--delay', `${index * 0.2}s`);
                    step.classList.add('visible'); // Додаємо клас
                });
                observer.unobserve(entry.target); // Відключаємо спостереження
            }
        });
    }, observerOptions);

    stepBlocks.forEach((block) => {
        observer.observe(block); // Починаємо спостереження
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const blocks = [
        document.querySelector('.additional-block.mission'),
        document.querySelector('.additional-block.why-us')
    ];

    const observerOptions = {
        root: null, // Вікно перегляду
        threshold: 0.2, // 20% блоку повинно бути видно
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const delay = blocks.indexOf(entry.target) * 700; // Затримка між блоками (700 мс)
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, delay);
                observer.unobserve(entry.target); // Відключаємо спостереження для блоку
            }
        });
    }, observerOptions);

    // Додаємо блоки до спостереження
    blocks.forEach((block) => observer.observe(block));
});



window.addEventListener("load", () => {
    const loadingScreen = document.getElementById("loading-screen");
    const maxWaitTime = 6000; // Максимальна затримка (6 секунд)

    // Функція для приховання завантажувального екрану
    const hideLoadingScreen = () => {
        if (loadingScreen) {
            loadingScreen.style.display = "none";
            document.body.classList.remove("loading"); // Дозволяємо скролінг
        }
    };

    // Запускаємо приховання з затримкою
    setTimeout(hideLoadingScreen, maxWaitTime);

    // Якщо сторінка завантажилась до завершення затримки, ховаємо екран
    hideLoadingScreen();
});

