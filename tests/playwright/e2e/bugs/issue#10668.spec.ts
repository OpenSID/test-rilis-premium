import { test, expect } from '@playwright/test';

test.describe('Bug/error: blank ketika tinjaupdf pada surat', () => {
  test('fix: perbaiki CSP header untuk memungkinkan blob resource', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10668',
    },
  }, async ({ page }) => {
    const response = await page.request.get('siteman');
    
    // Assert response status
    expect(response.status()).toBe(200);
    
    // Get Content-Security-Policy header
    const cspHeader = response.headers()['content-security-policy'];
    
    // Assert CSP header exists
    expect(cspHeader).toBeDefined();
    
    // Assert CSP header contains object-src 'self' blob:
    expect(cspHeader).toContain("object-src 'self' blob:");
    
    // Assert CSP header does NOT contain object-src 'none'
    expect(cspHeader).not.toContain("object-src 'none'");
  });
});
