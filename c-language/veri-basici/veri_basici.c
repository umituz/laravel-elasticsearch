#include <stdlib.h>
#include <time.h>
#include <stdio.h>
#include <string.h>
#include <locale.h>
#include <dirent.h>
#include <unistd.h>

int main(int argc, char **argv) {
	//setlocale(LC_ALL, "en_US.UTF-8");

	printf(" \t******** PROGRAM BAŞLIYOR ELASTİCSEARCH DB ******** \n");


	int start_time = time(NULL);

	printf("start_time : %d\n",start_time);


	int adet = 0;


	while(1){
		printf("TARANIYOR(%d) ... \n",adet);
		DIR *day_dir;
		struct dirent *day_dirent;
		day_dir = opendir("/home/umituz/projects/c-language/veri-olusturucu/tmp/veri/");
		if(day_dir){
			while((day_dirent = readdir(day_dir)) != NULL){
				if(strcmp(day_dirent->d_name, ".") && strcmp(day_dirent->d_name, "..")){

					char *command = malloc(sizeof(char)*10000);
					strcpy(command,"\0");


					sprintf(command,"curl -H \"Content-Type: application/json\" -XPOST 'http://localhost:9200/_bulk' --data-binary /home/umituz/projects/c-language/veri-olusturucu/tmp/veri/%s > /dev/null", day_dirent->d_name);
					printf("\tcommand : %s\n", command);
					system(command);

					sprintf(command,"rm -rf /home/umituz/projects/c-language/veri-olusturucu/tmp/veri/%s", day_dirent->d_name);
					printf("\tcommand : %s\n", command);
					system(command);

					strcpy(command,"\0");
					free(command);

					printf("\n\n");
				}
			}

			closedir(day_dir);
		}

		printf("start_time : %d\n",start_time);
		printf("finish_time : %d\n", time(NULL));
		printf("FARK : %d\n",time(NULL)-start_time);

		adet++;

		//if(adet > 300)
//			exit(0);
		printf("Yükleniyor...\n");
		sleep(1);
	}

    return 0;
}
