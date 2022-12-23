#include <stdlib.h>
#include <time.h>
#include <string.h>
#include <unistd.h>
#include <stdio.h>
#include "/usr/include/json-c/json.h"
#include "veri_olusturucu.h"

char *dosya_adi;
char *komut;
#define  DEBUG 1

int json_ekle(char message[1000000]){
    FILE *log = NULL;
    log = fopen(dosya_adi, "a+");
    if(log == NULL){
        printf("Hata : log dosyası açılamadı \n");
        return 0;
    }
	fprintf(log, "%s\n","{ \"index\":{ \"_index\": \"uyeler\" }}");
	fprintf(log, "%s\n",message);
	fclose(log);
	return 1;
}

void dosya_olustur(char *dosya_adi){

	//sprintf(komut,"/bin/mv %s /home/umituz/projects/c-language/veri-olusturucu/tmp/veri/.", dosya_adi);
	//system(komut);

	sprintf(dosya_adi,"/home/umituz/projects/c-language/veri-olusturucu/tmp/veri_tmp/log_%ld.json",  time(NULL));
	printf("dosya_adi : %s\n", dosya_adi);
}

int main(int argc, char** argv) {

	system("/bin/mkdir -p '/home/umituz/projects/c-language/veri-olusturucu/tmp/veri'");
	system("/bin/mkdir -p '/home/umituz/projects/c-language/veri-olusturucu/tmp/veri_tmp'");

	int i,n;
    time_t t;
    srand((unsigned) time(&t));
    int adet = 0,toplam = 0;;
    dosya_adi = malloc(sizeof(char)*200);

    komut = malloc(sizeof(char)*20000);

    dosya_olustur(dosya_adi);

	while(1){
	    char isim[200];
	    char soyad[200];
	    char cinsiyet[50];
	    char ulke[100];
	    char ip_address[100];
	    float latitude = 0.0;
	    float longitude = 0.0;
	    char konum[100];
	    int yas = 0;
	    int dogum_tarihi = 0;

	    if(rand()%2 == 1){
	    	/* ERKEK */
	    	strcpy(isim,erkek_isim[rand()%7363]);
	    	strcpy(cinsiyet,"erkek");
	    }else{
	    	/* KADIN  */
			strcpy(isim,kadin_isim[rand()%4577]);
			strcpy(cinsiyet,"kadın");
	    }

	    /* SOYADI  */
	    strcpy(soyad,soyisim[rand()%2446]);

	     /* SOYADI  */
	    strcpy(ulke,ulkeler[rand()%248]);

	    /* İP ADRESİ  */
	    sprintf(ip_address,"%d.%d.%d.%d", rand() % 256,rand() % 256,rand() % 256,rand() % 256);

	    /* KOORDİNATLAR  */
	    latitude = ((float)rand()/(float)(RAND_MAX)) * 90 * negatif[rand()%2];
	    longitude = ((float)rand()/(float)(RAND_MAX)) * 90 * negatif[rand()%2];
	    sprintf(konum,"%f,%f",latitude,longitude);

	     /* YAŞ  */
	    yas = (rand() % 70)+18;


	    /* DOĞUM YILI  */
	    dogum_tarihi = 2020 - yas;


	    if(DEBUG){
	    	printf("\n\n");
		    printf("isim : %s\n", isim);
		    printf("soyisim : %s\n", soyad);
		    printf("cinsiyet : %s\n", cinsiyet);
		    printf("ulke : %s\n", ulke);
		    printf("ip_address : %s\n", ip_address);
		    printf("konum : %s\n", konum);
		    printf("dogum_tarihi : %d\n", dogum_tarihi);
		    printf("yas : %d\n", yas);
		    printf("\n\n");
		}


	    json_object *json_data = json_object_new_object();

		json_object *json_string_isim = json_object_new_string(isim);
		json_object_object_add(json_data,"isim", json_string_isim);

		json_object *json_string_soyad = json_object_new_string(soyad);
		json_object_object_add(json_data,"soyad", json_string_soyad);

		json_object *json_string_cinsiyet = json_object_new_string(cinsiyet);
		json_object_object_add(json_data,"cinsiyet", json_string_cinsiyet);

		json_object *json_string_ulke = json_object_new_string(ulke);
		json_object_object_add(json_data,"ulke", json_string_ulke);

		json_object *json_string_ip_address = json_object_new_string(ip_address);
		json_object_object_add(json_data,"ip_address", json_string_ip_address);

		json_object *json_string_konum = json_object_new_string(konum);
		json_object_object_add(json_data,"konum", json_string_konum);

		json_object *json_int_dogum_tarihi = json_object_new_int(dogum_tarihi);
		json_object_object_add(json_data,"dogum_tarihi", json_int_dogum_tarihi);

		json_object *json_int_yas = json_object_new_int(yas);
		json_object_object_add(json_data,"yas", json_int_yas);

		json_object *json_int_ekleme_tarihi = json_object_new_int(time(NULL));
		json_object_object_add(json_data,"ekleme_tarihi", json_int_ekleme_tarihi);

		char *json_data_string = malloc(sizeof(char)* strlen(json_object_to_json_string(json_data))+100);
		strcpy(json_data_string, json_object_to_json_string(json_data));

		if(DEBUG)
			printf("json_data_string : %s\n", json_data_string);

		json_ekle(json_data_string);
		adet++;


		if(adet > 20000){
			adet = 0;
			toplam++;
			dosya_olustur(dosya_adi);

			printf("toplam : %d\n", toplam*20000);
			//sleep(1);
		}

		json_object_put(json_data);
		free(json_data_string);
	}


}

