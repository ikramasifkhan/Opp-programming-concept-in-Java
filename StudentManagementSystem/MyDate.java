/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package StudentManagementSystem;

/**
 *
 * @author Radi
 */
public class MyDate {
    public int year;
    public String month;
    public int date;
    
    public MyDate(int year, String month, int date){
        this.year=year;
        this.month=month;
        this.date=date;
    }
     public String hirringDate(){
        String hirringDate= +date+"/"+month+"/"+year;
        return hirringDate;
    }
}
