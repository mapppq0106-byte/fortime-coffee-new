import { NgModule, provideBrowserGlobalErrorListeners } from '@angular/core';
import { BrowserModule, provideClientHydration, withEventReplay } from '@angular/platform-browser';
// 1. Import FormsModule để sử dụng được [(ngModel)]
import { FormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing-module';
import { App } from './app';
import { Name } from './name/name';
import { ProductDetail } from './product-detail/product-detail';
import { Home } from './home/home';

@NgModule({
  declarations: [App, Name, ProductDetail, Home],
  imports: [
    BrowserModule,
    AppRoutingModule,
    // 2. Thêm FormsModule vào mảng imports
    FormsModule,
  ],
  providers: [provideBrowserGlobalErrorListeners(), provideClientHydration(withEventReplay())],
  bootstrap: [App],
})
export class AppModule {}
