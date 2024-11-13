class Pagination {
    constructor(paginationContainer) {
        this.paginationContainer = paginationContainer
    }
    generatePagination(dataPagination, callbackFunction) {
        const pagination = this.paginationContainer;
        pagination.innerHTML = '';

        const totalPages = dataPagination.meta.pagination.total_pages;
        const currentPage = dataPagination.meta.pagination.current_page;
        const paginationInfo = pagination.parentNode.querySelector('.pagination-info')
        
        paginationInfo.innerText = `Halaman ${currentPage} dari ${totalPages}`
        let pageNumber;
        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('li');
            pageLink.innerHTML = `<a class="cursor-pointer page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 `+(i === currentPage ? 'bg-primary-100 text-white hover:text-white hover:bg-primary-200' : 'bg-white hover:text-primary-200')+`">${i}</a>`;
            pageLink.className = 'page-item';
            pageLink.onclick = i === currentPage ? function () {} : function () {
                pageNumber = i
                callbackFunction(pageNumber)
            };
            if (i === 1) {
                const firstLink = pageLink.cloneNode()
                firstLink.className = 'page-item';
                firstLink.innerHTML = `<a class="cursor-pointer page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i data-feather="chevron-left" class="fas fa-arrow-left"></i></a>`;
                firstLink.onclick = currentPage > i ? function () {
                    pageNumber = 1
                    callbackFunction(pageNumber)
                } : function () {};
                pagination.appendChild(firstLink);

                const previousLink = pageLink.cloneNode()
                previousLink.className = 'page-item';
                previousLink.innerHTML = `<a class="cursor-pointer page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i data-feather="chevron-left" class="fas fa-chevron-left inline-block"></i></a>`;
                previousLink.onclick = currentPage > i ? function () {
                    pageNumber = currentPage - 1
                    callbackFunction(pageNumber)
                } : function () {};

                if(currentPage > 1){
                    pagination.appendChild(previousLink);
                }
                
            }
            pagination.appendChild(pageLink);
            if (i === totalPages) {                
                const nextLink = pageLink.cloneNode()
                nextLink.className = 'page-item';
                nextLink.innerHTML = `<a class="cursor-pointer page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-chevron-right inline-block"></i></a>`;
                nextLink.onclick = currentPage < totalPages ? function () {
                    pageNumber = currentPage + 1
                    callbackFunction(pageNumber)
                } : function () {};

                if(currentPage < totalPages){
                    pagination.appendChild(nextLink);
                }                

                const endLink = pageLink.cloneNode()
                endLink.className = 'page-item';
                endLink.innerHTML = `<a  class="cursor-pointer page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-arrow-right"></i></a>`;
                endLink.onclick = currentPage < totalPages ? function () {
                    pageNumber = totalPages
                    callbackFunction(pageNumber)
                } : function () {};
                pagination.appendChild(endLink);
            }
        }
    }
}