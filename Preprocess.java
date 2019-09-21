import java.io.*;
import java.util.*;

class Preprocess{
    public static void main(String[] args){
        try{
            String[] fileName = new String[]{"people.csv","event.csv","role.csv","dummy.csv"};
            FileInputStream f = new FileInputStream("raw.csv");
            Scanner sc = new Scanner(f);
            FileOutputStream fo = new FileOutputStream(fileName[3]);
            PrintStream out = new PrintStream(fo);
            int a = 0;
            while(sc.hasNextLine()){
                String line = sc.nextLine();
                if(line.charAt(0)=='+'){
                    fo = new FileOutputStream(fileName[a++]);
                    out = new PrintStream(fo);
                }
                else{
                    // String[] s = line.split("\\|");
                    // for(int i=1;i<s.length-2;i++)
                    //     out.print(s[i]+",");
                    out.println(line.substring(1,line.length()-1));
                }
            }
        }catch(Exception e){
            System.out.println(e.getMessage());
        }
    }
}