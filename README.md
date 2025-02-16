# Auto External Link Modifier

WordPress yazı ve sayfa içeriklerindeki dış bağlantılara özel `rel` ve `target="_blank"` ekleyen bir eklenti. İç bağlantılar ve resmi kurum web siteleri hariç tüm linkleri değiştirir.

## ✅ Tamamlanan Özellikler

1. Temel Özellikler:
   - ✅ WordPress yazı ve sayfa içeriklerini kontrol etme
   - ✅ Dış bağlantıları tespit etme
   - ✅ target="_blank" ekleme
   - ✅ İç bağlantıları hariç tutma
   - ✅ Resmi kurumları hariç tutma

2. REL Özellikleri Yönetimi:
   - ✅ WordPress admin panelinden yönetim
   - ✅ Özelleştirilebilir rel özellikleri:
     - ✅ noopener
     - ✅ noreferrer
     - ✅ nofollow
     - ✅ sponsored
     - ✅ ugc

3. Resmi Domain Kontrolü:
   - ✅ Global devlet domainleri (.gov, .gov.uk, .gov.au)
   - ✅ Global eğitim domainleri (.edu, .ac.uk, .edu.au)
   - ✅ Askeri domainler (.mil)
   - ✅ Ülke spesifik domainler (gov.tr, edu.tr, gov.de, edu.fr)

4. Çoklu Dil Desteği:
   - ✅ Türkçe dil dosyaları
   - ✅ Özellik açıklamaları çevirileri
   - ✅ Admin panel çevirileri

## 📋 Yapılması Planlananlar

1. İyileştirmeler:
   - [ ] Performans optimizasyonları
   - [ ] Hata yakalama geliştirmeleri
   - [ ] Domain listesi genişletme

2. Yeni Özellikler:
   - [ ] Özel domain listesi ekleme imkanı
   - [ ] Özel rel özelliği tanımlama
   - [ ] Gelişmiş ayar sayfası
   - [ ] Widget içerik desteği

## Gereksinimler

- WordPress 5.0+
- PHP 7.4+

## Kurulum

1. Eklenti zip dosyasını indirin
2. WordPress admin paneli > Eklentiler > Yeni Ekle
3. "Eklenti Yükle" butonuna tıklayın ve zip dosyasını seçin
4. "Şimdi Yükle" ve ardından "Etkinleştir"
5. Ayarlar > Dış Bağlantılar menüsünden REL özelliklerini yapılandırın

## Kullanım

1. Ayarlar > Dış Bağlantılar menüsünden REL özelliklerini seçin
2. Değişiklikleri kaydedin
3. Eklenti otomatik olarak yazı ve sayfalardaki dış bağlantıları işleyecektir

## Lisans

GPL v2 veya üzeri

## Geliştirici

[Hakan Tayfur](https://htayfur.com)

## Sürüm Geçmişi

### 1.0.0 
- İlk sürüm
- WordPress yazı ve sayfalarda dış bağlantı düzenleme
- Özelleştirilebilir REL özellikleri
- Global ve yerel resmi domain desteği
- Türkçe dil desteği
