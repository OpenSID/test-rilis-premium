class Pagination {
    constructor(paginationContainer) {
        this.paginationContainer = paginationContainer
    }
    generatePagination(dataPagination, callbackFunction) {
        const pagination = this.paginationContainer;
        pagination.innerHTML = '';

        const totalPages = dataPagination.meta.pagination.total_pages;
        const currentPage = dataPagination.meta.pagination.current_page;
        let pageNumber;
        for (let i = 1; i <= totalPages; i++) {				
            const pageLink = document.createElement('li');
            pageLink.innerHTML = `<a href="#" class="page-link" data-page="${i}">${i}</a>`;
            pageLink.className = (i === currentPage) ? 'active' : '';
            pageLink.onclick = i === currentPage ? function(){} : function() {					
                pageNumber = i
                callbackFunction(pageNumber)  
            };
            if(i === 1){
                const previousLink = pageLink.cloneNode()
                previousLink.className = (currentPage <= 1) ? 'disabled' : '';
                previousLink.innerHTML = `<a href="#" class="page-link" data-page="${currentPage > 1 ? currentPage - 1: 0}"> < </a>`;
                previousLink.onclick = currentPage > i ? function() {					
                    pageNumber = currentPage - 1
                    callbackFunction(pageNumber)
                } : function(){};
                pagination.appendChild(previousLink);
            }
            pagination.appendChild(pageLink);
            if(i === totalPages){
                const nextLink = pageLink.cloneNode()
                nextLink.className = (currentPage >= totalPages) ? 'disabled' : '';
                nextLink.innerHTML = `<a href="#" class="page-link" data-page="${currentPage < totalPages ? currentPage + 1: totalPages}"> > </a>`;
                nextLink.onclick = currentPage < totalPages ? function() {					
                    pageNumber = currentPage + 1
                    callbackFunction(pageNumber) 
                } : function(){};
                pagination.appendChild(nextLink);
            }
        }
    }
}
