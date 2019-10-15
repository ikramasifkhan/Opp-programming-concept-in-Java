package StudentManagementSystem;
/**
 * @author Radi
 */
public class Faculty extends Employee{
    private String officeHoure;
    private String rank;
    public Faculty(String name, String address, String phoneNumber, String emailAddress, String officeAddress, int salary, String officeHoure, String rank) {
        super(name, address, phoneNumber, emailAddress, officeAddress, salary);
        this.officeHoure=officeHoure;
        this.rank=rank;
    }
    
    @Override
    public String toString(){
        String facultyInfo=
                "Faculty name :"+name
                +"\n Faculty address:"+address
                +"\n Office address:"+officeAddress
                +"\nContact number :"+phoneNumber
                +"\nEmail address :"+emailAddress
                +"\n Salary"+ salary
                +"\n Office hours"+officeHoure
                +"\n Rank"+rank;
        return facultyInfo;
    }
    
}
