import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { Name } from './name/name';
import { ProductDetail } from './product-detail/product-detail';
import { Home } from './home/home'; // Import Home component

const routes: Routes = [
  { path: 'home', component: Home },
  { path: 'product', component: Name }, // Trang danh sách sản phẩm
  { path: 'product/:id', component: ProductDetail }, // Chi tiết sản phẩm
  { path: '', redirectTo: '/home', pathMatch: 'full' }, // Mặc định vào Home
  { path: '**', redirectTo: '/home' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }